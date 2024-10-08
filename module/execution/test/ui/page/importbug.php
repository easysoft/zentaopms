<?php
class importBugPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'firstCheckbox' => "//*[@id='table-execution-importbug']/div[2]/div[1]/div/div[1]/div/div",
            'firstId'       => "//*[@id='table-execution-importbug']/div[2]/div[1]/div/div[1]/div",
            'num'           => "//*[@id='table-execution-importbug']/div[3]/div[2]/strong",
            'saveBtn'       => "//*[@id='table-execution-importbug']/div[3]/nav[1]/button"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
