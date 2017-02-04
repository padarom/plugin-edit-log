<?php namespace wcf\system\cronjob;

use wcf\data\cronjob\Cronjob;
use wcf\system\WCF;

class PruneOldACPEditsCronjob extends AbstractCronjob
{
    /**
     * @see	\wcf\system\cronjob\ICronjob::execute()
     */
    public function execute(Cronjob $cronjob)
    {
        parent::execute($cronjob);

        // PRUNE ACP EDITS
        // after "acp_edit_log_prune_after_days" DAYS
        /*
        $sql = "DELETE FROM	wcf".WCF_N."_search
			WHERE		searchTime < ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute(array(
            (TIME_NOW - 86400)
        ));
        */
    }
}
