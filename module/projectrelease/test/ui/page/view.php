<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'basic'               => "//*[@id='mainContent']/div[2]/div/div/div/div/ul/li[4]/a/span",
            'basicreleasename'    => "//*[@id='releaseInfo']/div[2]/div[1]/table/tbody/tr[2]/td",
            'basicstatus'         => "//*[@id='releaseInfo']/div[2]/div[1]/table/tbody/tr[4]/td",
            'basicplandate'       => "//*[@id='releaseInfo']/div[2]/div[1]/table/tbody/tr[5]/td",
            'linkStoryBtn'        => "//*[@id='mainContent']/div[2]/div/div/div/div[2]/div/div/button/span",
            'linkStoryBtnBottom'  => "//*[@id='unlinkStoryList']/div[3]/nav/button/span",
            'basicreleasedate'    => "//*[@id='releaseInfo']/div[2]/div[1]/table/tbody/tr[6]/td",
            'searchBtn'           => "//*[@class='btn primary']",
            'selectAllStory'      => "//*[@id='unlinkStoryList']/div[3]/div/div/label",
            'finishedStoryNum'    => "//*[@id='finishedStoryDTable']/div[3]/div[2]/strong[1]",
            'unlinkFirBtn'        => "//*[@id='finishedStoryDTable']/div[2]/div[3]/div/div/div/nav/a/i",
            'allFinishedStoryBtn' => "//*[@id='finishedStoryDTable']/div[3]/div/div/label",
            'batchUnlinkBtn'      => "//*[@id='finishedStoryDTable']/div[3]/nav[1]/button[1]/span",
            /*发布关联Bug的元素*/
            'resolvedBugTab'    => "//*[@id='mainContent']/div[2]/div/div/div/div/ul/li[2]/a/span",
            'linkBugBtn'        => "//*[@id='mainContent']/div[2]/div/div/div/div[2]/div[2]/div/button/span",
            'selectAllBug'      => "//*[@id='unlinkBugList']/div[3]/div/div/label",
            'linkBugBtnBottom'  => "//*[@id='unlinkBugList']/div/nav/button/span",
            'resolvedBugNum'    => "//*[@id='resolvedBugDTable']/div[3]/div[2]/strong",
            'unlinkFirBugBtn'   => "//*[@id='resolvedBugDTable']/div[2]/div[3]/div/div/div/nav/a/i",
            'allResolvedBugBtn' => "//*[@id='resolvedBugDTable']/div[3]/div/div/label",
            'batchUnlinkBugBtn' => "//*[@id='resolvedBugDTable']/div[3]/nav[1]/button[1]/span",
            /*导出HTML*/
            'exportBtn'      => "//*[@id='mainContent']/div[2]/div/div/div/div[2]/div[2]/div/a/span",
            'exportData'     => "//*[@class='panel-body']/form/div[2]/div/div/span",
            'exportBtnAlert' => "//*[@class='form-row']/div/button",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
