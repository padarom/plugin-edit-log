<?php namespace wcf\acp\page;

use wcf\data\acp\editlog\ACPEditLog;
use wcf\data\acp\editlog\ACPEditLogList;
use wcf\data\user\User;
use wcf\page\MultipleLinkPage;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Shows cronjob log information.
 *
 * @author	Marcel Werk
 * @copyright	2001-2015 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	acp.page
 * @category	Community Framework
 */
class ACPEditLogViewPage extends MultipleLinkPage
{
    public $templateName = 'acpEditLogView';

    /**
     * @see	\wcf\page\AbstractPage::$activeMenuItem
     */
    public $activeMenuItem = 'wcf.acp.menu.link.log.acpedit';

    /**
     * @see	\wcf\page\AbstractPage::$neededPermissions
     */
    public $neededPermissions = ['admin.system.canViewACPEditLog'];

    /**
     * @see	\wcf\page\MultipleLinkPage::$itemsPerPage
     */
    public $itemsPerPage = 25;

    /**
     * @see	\wcf\page\MultipleLinkPage::$objectListClassName
     */
    public $objectListClassName = ACPEditLogList::class;

    /**
     * @see	\wcf\page\MultipleLinkPage::$sqlOrderBy
     */
    public $sqlOrderBy = 'time DESC';

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $logDateStart;

    /**
     * @var string
     */
    public $logDateEnd;

    /**
     * @var string
     */
    public $objectType;

    /**
     * @var int
     */
    public $searchObjectID;

    public function readParameters()
    {
        parent::readParameters();

        if (isset($_POST['username'])) $this->username = StringUtil::trim($_POST['username']);
        if (isset($_POST['logDateStart'])) $this->logDateStart = StringUtil::trim($_POST['logDateStart']);
        if (isset($_POST['logDateEnd'])) $this->logDateEnd = StringUtil::trim($_POST['logDateEnd']);
        if (isset($_POST['objectType'])) $this->objectType = StringUtil::trim($_POST['objectType']);
    }

    protected function initObjectList()
    {
        parent::initObjectList();

        $conditionBuilder = $this->objectList->getConditionBuilder();

        if (!empty($this->username)) {
            if ($user = User::getUserByUsername($this->username)) {
                $conditionBuilder->add('userID = ?', [$user->getUserID()]);
            } else {
                $conditionBuilder->add('username = ?', [$this->username]);
            }
        }

        if (!empty($this->objectType)) {
            $conditionBuilder->add('objectClass = ?', [$this->objectType]);
        }

        if (!empty($this->logDateStart)) {
            $unix = strtotime($this->logDateStart);
            $time = date("Y-m-d H:i:s", $unix);
            $conditionBuilder->add('time > ?', [$time]);
        }

        if (!empty($this->logDateEnd)) {
            $unix = strtotime($this->logDateEnd);
            $time = date("Y-m-d H:i:s", $unix);
            $conditionBuilder->add('time < ?', [$time]);
        }
    }

    /**
     * @see	\wcf\page\IPage::assignVariables()
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign(array(
            'username' => $this->username,
            'logDateStart' => $this->logDateStart,
            'logDateEnd' => $this->logDateEnd,
            'objectID' => $this->searchObjectID,
            'objectType' => $this->objectType,
            'objectTypes' => array_merge(['' => 'All Types'], ACPEditLog::getAvailableClasses()),
        ));
    }
}
