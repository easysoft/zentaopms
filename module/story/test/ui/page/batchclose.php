<?php
class batchClosePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'batchClosedReason' => "/html/body/div[1]/div/div/div/div[2]/form/div[1]/table/tbody/tr/td[6]/div[2]/div[1]/div",
            'batchClosedSave'   => "//*[@id='zin_story_batchclose_formBatch']/div[2]/button[1]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
