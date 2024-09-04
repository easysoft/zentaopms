<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'planTitle'      => "//*[@id='planInfo']/table/tbody/tr[1]/td",
            'delTag'         => "//*[@id='mainContent']/div[1]/div/span[2]",
            'confirm'        => "//button[@z-key='confirm']",
            'status'         => "//*[@id='planInfo']/table/tbody/tr[last()-1]/td",
            'begin'          => "//*[@id='planInfo']/table/tbody/tr[2]/td",
            'end'            => "//*[@id='planInfo']/table/tbody/tr[3]/td",
            'parent'         => "//*[@id='planInfo']/table/tbody/tr[2]/td/a",
            'selectAllStory' => "//*[@id='unlinkStoryList']/div[1]/div[1]/div/div[1]/div/div/label",
            'selectAllBug'   => "//*[@id='unlinkBugList']/div[1]/div[1]/div/div[1]/div/div/label",
            'checkInfoStory' => "//*[@id='storyDTable']/div[3]/div[2]",
            'checkInfoBug'   => "//*[@id='bugDTable']/div[3]/div[2]",
            'allLinkedStory' => "//*[@id='storyDTable']/div[1]/div[1]/div/div[1]/div/div/label",
            'allLinkedBug'   => "//*[@id='bugDTable']/div[1]/div[1]/div/div[1]/div/div/label",
            'unlinkFirStory' => "//*[@id='storyDTable']/div[2]/div[3]/div/div[1]/div/nav/a",
            'unlikFirBug'    => "//*[@id='bugDTable']/div[2]/div[3]/div/div[1]/div/nav/a",
            'storyLinkNum'   => "//*[@id='storyDTable']/div[3]/nav/div[1]",
            'bugLinkNum'     => "//*[@id='bugDTable']/div[3]/nav/div[1]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
