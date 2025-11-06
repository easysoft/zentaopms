<?php
class teamPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            // 列选择器（与 dtable 的 data-col 对齐）
            'id'            => "//div[@data-col='id' and @data-row!='HEADER']",
            'realname'      => "//div[@data-col='realname' and @data-row!='HEADER']",
            'account'       => "//div[@data-col='account' and @data-row!='HEADER']",
            'gender'        => "//div[@data-col='gender' and @data-row!='HEADER']",
            'role'          => "//div[@data-col='role' and @data-row!='HEADER']",
            'phone'         => "//div[@data-col='phone' and @data-row!='HEADER']",
            'qq'            => "//div[@data-col='qq' and @data-row!='HEADER']",
            'email'         => "//div[@data-col='email' and @data-row!='HEADER']",
            'last'          => "//div[@data-col='last' and @data-row!='HEADER']",
            'visits'        => "//div[@data-col='visits' and @data-row!='HEADER']",

            // 访问受限
            'denied'        => '//*[@id="denyBox"]/div[1]/div',

            // 分页控件（table id 按模块约定）
            'pagerSizeMenu' => "//button[contains(@class, 'pager-size-menu')]",
            'firstPage'     => "//*[@id='table-my-team']/div[3]/nav/a[1]",
            'prevPage'      => "//*[@id='table-my-team']/div[3]/nav/a[2]",
            'nextPage'      => "//*[@id='table-my-team']/div[3]/nav/a[3]",
            'lastPage'      => "//*[@id='table-my-team']/div[3]/nav/a[4]"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
