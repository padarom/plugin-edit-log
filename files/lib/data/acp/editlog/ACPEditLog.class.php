<?php namespace wcf\data\acp\editlog;

use wcf\data\DatabaseObject;
use wcf\data\user\group\UserGroup;
use wcf\data\user\User;
use wcf\system\WCF;
use wcf\util\ClassUtil;

class ACPEditLog extends DatabaseObject
{
    protected static $databaseTableName = 'acp_edit_log';

    protected static $databaseTableIndexName = 'logID';

    public function getUsername()
    {
        if ($user = new User($this->userID)) {
            return $user->getUsername();
        }

        return $this->username;
    }

    protected function handleData($data)
    {
        parent::handleData($data);

        $this->data['changes'] = unserialize($data['changes']);
        if (isset($this->data['changes']['usergroups']['added'])) {
            foreach ($this->data['changes']['usergroups']['added'] as &$added) {
                $added = new UserGroup($added);
            }
        }

        if (isset($this->data['changes']['usergroups']['removed'])) {
            foreach ($this->data['changes']['usergroups']['removed'] as &$removed) {
                $removed = new UserGroup($removed);
            }
        }
    }

    public function getEditedObject()
    {
        $objectClass = $this->objectClass;

        if (!class_exists($objectClass) || !ClassUtil::isInstanceOf($objectClass, DatabaseObject::class)) {
            return null;
        }

        $object = new $objectClass($this->objectID);
        return $object;
    }

    public function getObjectDescriptor()
    {
        return self::getDescriptor($this->objectClass);
    }

    protected static function getDescriptor($class)
    {
        $class = strtolower(str_replace('\\', '.', $class));
        return WCF::getLanguage()->get('wcf.acp.editlog.descriptor.' . $class);
    }

    public function getClassDescriptor()
    {
        return strtolower(str_replace('\\', '.', $this->objectClass));
    }

    public static function getAvailableClasses()
    {
        $list = [];

        $sql = "SELECT	DISTINCT(objectClass)
				FROM	".static::getDatabaseTableName();
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute();
        while ($row = $statement->fetchArray()) {
            $class = $row['objectClass'];
            $list[$class] = self::getDescriptor($class);
        }

        return $list;
    }
}