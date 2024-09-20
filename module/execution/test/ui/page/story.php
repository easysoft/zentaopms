<?php
class storyPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 标签 */
            'allTab'       => "//*[@data-page='execution-story']//div[@id='featureBar']/menu/li[1]/a",
            'unclosedTab'  => "//*[@data-page='execution-story']//div[@id='featureBar']/menu/li[2]/a",
            'draftTab'     => "//*[@data-page='execution-story']//div[@id='featureBar']/menu/li[3]/a",
            'reviewingTab' => "//*[@data-page='execution-story']//div[@id='featureBar']/menu/li[3]/a",
            'num'          => "//*[@id='table-execution-story']/div[3]/div[2]/strong[1]",
            /* 列表 */
            'firstName'      => "//*[@id='table-execution-story']/div[2]/div[1]/div/div[2]/div/a",
            'firstUnlinkBtn' => "//*[@id='table-execution-story']/div[2]/div[3]/div/div/div/nav/a[5]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
