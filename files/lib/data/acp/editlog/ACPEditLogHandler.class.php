<?php namespace wcf\data\acp\editlog;

use wcf\data\DatabaseObject;
use wcf\data\user\User;
use wcf\system\SingletonFactory;

class ACPEditLogHandler extends SingletonFactory
{
    public function log(DatabaseObject $object, User $user, $type = 'edit', $changes = [])
    {
        $data = [
            'username' => $user->getUsername(),
            'userID' => $user->getUserID(),
            'objectID' => $object->getObjectID(),
            'objectClass' => get_class($object),
            'type' => $type,
            'changes' => serialize($changes),
            'time' => date("Y-m-d H:i:s"),
        ];

        $objectAction = new ACPEditLogAction([], 'create', [
            'data' => $data,
        ]);
        $objectAction->executeAction();
    }
}