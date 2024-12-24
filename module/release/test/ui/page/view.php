<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'releaseInfo'    => "//*[@id='releaseTabs']/div[1]/ul/li[4]/a",
            'releasedStatus' => "//*[@id='releaseInfo']/div[2]/div[1]/table/tbody/tr[4]/td",
            'planedDate'     => "//*[@id='releaseInfo']/div[2]/div[1]/table/tbody/tr[5]/td",
            'releasedDate'   => "//*[@id='releaseInfo']/div[2]/div[1]/table/tbody/tr[6]/td",
            /*导出HTML*/
            'exportBtn'      => "//*[@id='mainContent']/div[2]/div/div/div/div[2]/div[2]/div/a/span",
            'exportData'     => "//*[@class='panel-body']/form/div[2]/div/div/span",
            'exportBtnAlert' => "//*[@class='form-row']/div/button",
            /*发布关联需求*/
            'linkStoryBtn'        => "//*[@id='mainContent']/div[2]/div/div/div/div[2]/div/div/button/span",
            'linkStoryBtnBottom'  => "//*[@id='unlinkStoryList']/div[3]/nav/button/span",
            'searchBtn'           => "//*[@class='btn primary']",
            'selectAllStory'      => "//*[@id='unlinkStoryList']/div[3]/div/div/label",
            'finishedStoryNum'    => "//*[@id='finishedStoryDTable']/div[3]/div[2]/strong[1]",
            'unlinkFirBtn'        => "//*[@id='finishedStoryDTable']/div[2]/div[3]/div/div/div/nav/a/i",
            'allFinishedStoryBtn' => "//*[@id='finishedStoryDTable']/div[3]/div/div/label",
            'batchUnlinkBtn'      => "//*[@id='finishedStoryDTable']/div[3]/nav[1]/button[1]/span"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
