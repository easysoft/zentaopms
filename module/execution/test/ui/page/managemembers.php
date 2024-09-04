<?php
class managemembersPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 维护团队成员页面元素 */
            'firstAccount'    => "//*[@id='realname0']",                                                              //第一个用户
            'firstNullAccount'=> "(//div[@id='teamForm']//input[starts-with(@name, 'account[') and not(@value)])[1]", //第一个为空的用户
            'firstDelBtn'     => "//*[@id='realname0']/../../td[6]/div/button[2]",                                    //第一行的删除按钮
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
