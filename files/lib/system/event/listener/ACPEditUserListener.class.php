<?php namespace wcf\system\event\listener;

use wcf\acp\form\UserEditForm;
use wcf\data\acp\editlog\ACPEditLogHandler;
use wcf\data\user\group\UserGroup;
use wcf\data\user\option\UserOptionList;
use wcf\data\user\User;
use wcf\system\WCF;

class ACPEditUserListener implements IParameterizedEventListener
{
    /**
     * Executes this action.
     *
     * @param	UserEditForm $eventObj	Object firing the event
     * @param	string		$className	class name of $eventObj
     * @param	string		$eventName	name of the event fired
     * @param	array		&$parameters	given parameters
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        /* @var $user User */
        $user = $eventObj->user->getDecoratedObject();
        $this->getChanges($eventObj, $user, $changes);

        $handler = ACPEditLogHandler::getInstance();
        $handler->log($user, WCF::getUser(), 'edit', $changes);
    }

    /**
     * @param UserEditForm $object
     * @param User $user
     * @param $changes
     */
    protected function getChanges($object, $user, &$changes)
    {
        $changes = [];

        /** USERGROUP CHANGES */
        // Get special groups
        $ignoreGroups = array_map(function ($item) { return $item->groupID; }, UserGroup::getAccessibleGroups([1, 2, 3]));

        $usergroups = $user->getGroupIDs();
        $removedGroups = array_diff($usergroups, $object->groupIDs, $ignoreGroups);
        $addedGroups = array_diff($object->groupIDs, $usergroups, $ignoreGroups);

        if (count($removedGroups) || count($addedGroups)) {
            $changes['usergroups'] = [];
            if (count($removedGroups)) $changes['usergroups']['removed'] = $removedGroups;
            if (count($addedGroups)) $changes['usergroups']['added'] = $addedGroups;
        }

        /** USER NAME AND EMAIL CHANGE */
        self::compare('email', $user->email, $object->email, $changes);
        self::compare('username', $user->getUsername(), $object->username, $changes);
        self::compare('userTitle', $user->userTitle, $_POST['userTitle'], $changes);

        /** USER OPTION CHANGES */
        $optionList = new UserOptionList;
        $optionList->readObjects();

        foreach ($optionList->getObjects() as $option) {
            $name = $option->optionName;
            if (isset($_POST['values'][$name])) {
                if (!isset($changes['options'])) $changes['options'] = [];
                self::compare($name, $user->$name, $_POST['values'][$name], $changes['options']);
            }
        }
    }

    protected static function compare($name, $a, $b, &$changes)
    {
        if ($a != $b) {
            $changes[$name] = ['before' => $a, 'after' => $b];
        }
    }
}