<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'dropMenu'   => "//*[@id='mainMenu']/button",
            'allProduct' => "//li[@class='menu-item item'][1]",
            /* 标签 */
            'total'      => "//*[@data-id='totalStatus']",
            'myinvolved' => "//*[@data-id='myinvolved']",
            'wait'       => "//*[@data-id='wait']",
            'doing'      => "//*[@data-id='doing']",
            'blocked'    => "//*[@data-id='blocked']",
            'done'       => "//*[@data-id='done']",
            /* 列表下方统计数据 */
            'num' => "//*[@id='table-testtask-browse']/div[3]/div[1]/strong[1]",
            /* 列表 */
            'firstID'        => "//*[@id='table-testtask-browse']/div[2]/div[1]/div/div[1]/div",
            'firstName'      => "//*[@id='table-testtask-browse']/div[2]/div[1]/div/div[2]/div/a",
            'firstDeleteBtn' => "//*[@id='table-testtask-browse']/div[2]/div[3]/div/div[1]/div/nav/a[last()]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
