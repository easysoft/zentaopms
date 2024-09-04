<?php
class managemembersPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 团队管理页面第一个为空的用户input */
            'account' => "(//div[@id='teamForm']//input[starts-with(@name, 'account[') and not(@value)])[1]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
