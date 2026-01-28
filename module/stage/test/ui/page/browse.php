<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'createBtn'      => "//*[@id='actionBar']/a[2]/span",
            'stageName'      => "//*[@id='table-stage-browse']/div[2]/div[1]/div/div[21]/div",
            'stageType'      => "//*[@id='table-stage-browse']/div[2]/div[2]/div/div[7]/div/span",
            'editBtn'        => "//*[@id='table-stage-browse']/div[2]/div[3]/div/div[1]/div/nav/a[1]/i",
            'stageNameA'     => "//*[@id='table-stage-browse']/div[2]/div[1]/div/div[3]/div",
            'stageTypeA'     => "//*[@id='table-stage-browse']/div[2]/div[2]/div/div[1]/div/span",
            'batchCreateBtn' => "//*[@id='actionBar']/a[1]/span",
            'deleteBtn'      => "//*[@id='table-stage-browse']/div[2]/div[3]/div/div[6]/div/nav/a[2]/i",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
