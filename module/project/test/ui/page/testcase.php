<?php
class testcasePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 标签 */
            'allTab'          => "//*[@id='featureBar']/menu/li[1]/a",
            'waitingTab'      => "//*[@id='featureBar']/menu/li[2]/a",
            'storyChangedTab' => "//*[@id='featureBar']/menu/li[4]/a",
            'storyNoCaseTab'  => "//*[@id='featureBar']/menu/li[5]/a",
            'testcaseNum'     => "//*[@id='table-project-testcase']/div[3]/nav/div[1]",
            'zerocaseNum'     => "//*[@id='table-testcase-zerocase']/div[3]/nav/div[1]",
            /* 1.5级产品导航 */
            'dropMenu'        => "//*[@id='pick-project-menu']",
            'firstProduct'    => "//*[@id='pick-pop-project-menu']/div[2]/div/div[1]/menu/li[2]/div",
            'secondProduct'   => "//*[@id='pick-pop-project-menu']/div[2]/div/div[1]/menu/li[3]/div",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
