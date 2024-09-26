<?php
class batchEditPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'batchSource' => "/html/body/div[1]/div/div/div/div[2]/form/div[1]/table/tbody/tr/td[10]/div/div",
            'batchEditSave'   => "//*[@id='zin_story_batchedit_formBatch']/div[2]/button[1]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
