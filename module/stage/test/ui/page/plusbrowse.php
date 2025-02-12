<?php
class plusbrowsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'createBtn'      => "//*[@id='mainContent']/div[1]/div/div[2]/a[2]/span",
            'stageName'      => "//*[@id='table-stage-plusbrowse']/div[2]/div[2]/div/div[13]/div",
            'stageType'      => "//*[@id='table-stage-plusbrowse']/div[2]/div[2]/div/div[14]/div/span",
            'plusEditBtn'    => "//*[@id='table-stage-plusbrowse']/div[2]/div[3]/div/div/div/nav/a[1]/i",
            'stageNameB'     => "//*[@id='table-stage-plusbrowse']/div[2]/div[2]/div/div[1]/div",
            'stageTypeB'     => "//*[@id='table-stage-plusbrowse']/div[2]/div[2]/div/div[2]/div/span",
            'batchCreateBtn' => "//*[@id='mainContent']/div[1]/div/div[2]/a[1]/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
