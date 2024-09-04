<?php
class teamPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 团队成员列表页元素 */
            'firstUser'      => "(//*[@id='table-execution-team']//div[@data-col='realname'])[2]/div/a",      //第一个用户
            'lastUser'       => "(//*[@id='table-execution-team']//div[@data-col='realname'])[last()]/div/a", //最后一个用户
            'firstRemoveBtn' => "//*[@id='table-execution-team']/div[2]/div[2]/div/div[1]/div/nav/a",         //第一个移除按钮
            'num'            => "//*[@id='featureBar']/menu/li/a/span[2]",                                    //统计数据
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
