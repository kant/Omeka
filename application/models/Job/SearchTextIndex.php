<?php
class Job_SearchTextIndex extends Omeka_Job_AbstractJob
{
    /**
     * Bulk index all valid records.
     */
    public function perform()
    {
        // Truncate the `search_texts` table before indexing to clean out 
        // obsolete records.
        $sql = "TRUNCATE TABLE {$this->_db->SearchText}";
        $this->_db->query($sql);
        
        foreach (Mixin_Search::getSearchRecordTypes() as $recordType) {
            
            if (!class_exists($recordType)) {
                // The class does not exist or cannot be found.
                continue;
            }
            $record = new $recordType;
            if (!($record instanceof Omeka_Record_AbstractRecord)) {
                // The class is not a valid record.
                continue;
            }
            if (!is_callable(array($record, 'addSearchText'))) {
                // The record does not implement the search mixin.
                continue;
            }
            
            $pageNumber = 1;
            $recordTable = $record->getTable();
            // Query a limited number of rows at a time to prevent memory issues.
            while ($recordObjects = $recordTable->fetchObjects($recordTable->getSelect()->limitPage($pageNumber, 100))) {
                foreach ($recordObjects as $recordObject) {
                    // Save the record object, which indexes its search text.
                    $recordObject->save();
                }
                $pageNumber++;
            }
        }
    }
}
