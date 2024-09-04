<?php
class managemembersPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'account' => "(//div[@id='teamForm']//input[starts-with(@name, 'account[') and not(@value)])[1]", //团队管理页面第一个为空的用户
            'user'    => "(//*[@id='table-execution-team']//div[@data-col='realname'])[last()]/div/a",        //团队列表页面最后一个用户
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
