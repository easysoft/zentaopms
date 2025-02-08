<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'createBtn'      => "//*[@id='mainContent']/div[1]/div/div[2]/a[2]/span",
            'stageName'      => "//*[@id='table-stage-browse']/div[2]/div[2]/div/div[13]/div",
            'stageType'      => "//*[@id='table-stage-browse']/div[2]/div[2]/div/div[14]/div/span",
            'editBtn'        => "//*[@id='table-stage-browse']/div[2]/div[3]/div/div[1]/div/nav/a[1]/i",
            'stageNameA'     => "//*[@id='table-stage-browse']/div[2]/div[2]/div/div[1]/div",
            'stageTypeA'     => "//*[@id='table-stage-browse']/div[2]/div[2]/div/div[2]/div/span",
            'batchCreateBtn' => "//*[@id='mainContent']/div[1]/div/div[2]/a[1]/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
