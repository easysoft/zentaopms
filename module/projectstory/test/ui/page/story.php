<?php
class storyPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 标签 */
            'allTab'               => "//*[@id='main']/div/div[1]/div[1]/menu/li[1]/a/span[1]",
            'unclosedTab'          => "//*[@id='main']/div/div[1]/div[1]/menu/li[2]/a/span[1]",
            'draftTab'             => "//*[@id='main']/div/div[1]/div[1]/menu/li[3]/a/span[1]",
            'reviewingTab'         => "//*[@id='main']/div/div[1]/div[1]/menu/li[4]/a/span[1]",
            'changingTab'          => "//*[@id='main']/div/div[1]/div[1]/menu/li[5]/a/span[1]",
            'moreTab'              => "//*[@id='main']/div/div[1]/div[1]/menu/li[6]/a/span[1]",
            'closedTab'            => "//*[@id='more']/menu/menu/li[1]/a/div/div",
            'linkedExecutionTab'   => "//*[@id='more']/menu/menu/li[2]/a/div/div",
            'unlinkedExecutionTab' => "//*[@id='more']/menu/menu/li[3]/a/div/div",
            'num'                  => "//*[@id='stories_table']/div/div[3]/div[2]/strong[1]",
            /* 导出 */
            'format'         => "//*[@data-name='fileType']/div/div/span[1]",
            'encoding'       => "//*[@data-name='encode']/div/div/span[1]",
            'data'           => "//*[@data-name='exportType']/div/div/span[1]",
            'exportBtnAlert' => "//*[@class='modal-content']/div[3]/div/div/form/div[8]/div/div/button/span"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
