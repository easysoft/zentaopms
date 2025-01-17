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
            'myinvolved' => "//*[@data-id='myinvolved']",
            'wait'       => "//*[@data-id='wait']",
            'doing'      => "//*[@data-id='doing']",
            'blocked'    => "//*[@data-id='blocked']",
            'done'       => "//*[@data-id='done']",
            /* 标签后数量 */
            'totalNum'      => "//*[@data-id='totalStatus']/span[2]",
            'myinvolvedNum' => "//*[@data-id='myinvolved']/span[2]",
            'waitNum'       => "//*[@data-id='wait']/span[2]",
            'doingNum'      => "//*[@data-id='doing']/span[2]",
            'blockedNum'    => "//*[@data-id='blocked']/span[2]",
            'doneNum'       => "//*[@data-id='done']/span[2]",
            /* 列表 */
            'firstID'        => "//*[@id='table-testtask-browse']/div[2]/div[1]/div/div[1]/div",
            'firstName'      => "//*[@id='table-testtask-browse']/div[2]/div[1]/div/div[2]/div/a",
            'firstDeleteBtn' => "//*[@id='table-testtask-browse']/div[2]/div[3]/div/div[1]/div/nav/a[last()]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
