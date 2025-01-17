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
            'totalNum'   => "//*[@data-id='totalStatus']/span[2]",
            'myinvolved' => "//*[@data-id='myinvolved']/span[2]",
            'wait'       => "//*[@data-id='wait']/span[2]",
            'doing'      => "//*[@data-id='doing']/span[2]",
            'blocked'    => "//*[@data-id='blocked']/span[2]",
            'done'       => "//*[@data-id='done']/span[2]",
            'startTime'  =>
            /* 列表 */
            'firstID'        => "//*[@id='table-testtask-browse']/div[2]/div[1]/div/div[1]/div",
            'firstName'      => "//*[@id='table-testtask-browse']/div[2]/div[1]/div/div[2]/div/a",
            'firstDeleteBtn' => "//*[@id='table-testtask-browse']/div[2]/div[3]/div/div[1]/div/nav/a[last()]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
