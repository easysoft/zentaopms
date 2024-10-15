<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'basic'          => "//*[@id='mainContent']/div[2]/div/div/div/div/ul/li[4]/a/span",
            'basicBuildName' => "//*[@class='section']/table/tbody[1]/tr[2]/td",
            'basicExecution' => "//*[@class='section']/table/tbody[1]/tr[3]/td",
            /* 版本关联研发需求的元素  */
            'linkStoryBtn'        => "//*[@id='mainContent']/div[2]/div/div/div/div[2]/div/div/button/span",
            'searchBtn'           => "//*[@class='tab-content']/div/div/div/form/div[2]/button",
            'selectAllStory'      => "//*[@id='unlinkStoryList']/div[3]/div/div/label",
            'linkStoryBtnBottom'  => "//*[@id='unlinkStoryList']/div[3]/nav[1]/button/span",
            'finishedStoryNum'    => "//*[@id='linkStoryDTable']/div[3]/div[2]/strong[1]",
            'unlinkFirBtn'        => "//*[@id='linkStoryDTable']/div[2]/div[3]/div/div/div/nav/a/i",
            'allFinishedStoryBtn' => "//*[@id='linkStoryDTable']/div[3]/div/div/label",
            'batchUnlinkBtn'      => "//*[@id='linkStoryDTable']/div[3]/nav[1]/button[1]/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
