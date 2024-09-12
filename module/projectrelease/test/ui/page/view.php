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
            'confirm'             => "//div[@class='modal']//button[text()='确定']",
            'basicreleasedate'    => "//*[@id='releaseInfo']/div[2]/div[1]/table/tbody/tr[6]/td",
            'searchBtn'           => "//*[@class='btn primary']",
            'selectAllStory'      => "//*[@id='unlinkStoryList']/div[3]/div/div/label",
            'finishedStoryNum'    => "//*[@id='finishedStoryDTable']/div[3]/div[2]/strong[1]",
            'unlinkFirBtn'        => "//*[@id='finishedStoryDTable']/div[2]/div[3]/div/div/div/nav/a/i",
            'allFinishedStoryBtn' => "//*[@id='finishedStoryDTable']/div[3]/div/div/label",
            'batchUnlinkBtn'      => "//*[@id='finishedStoryDTable']/div[3]/nav[1]/button[1]/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
