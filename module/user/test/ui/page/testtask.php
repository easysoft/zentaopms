<?php
class testtaskPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $this->xpath = array(
            // 顶部用户选择器
            'userPicker'    => '//div[contains(@class, "picker-select-single")]',

            // 子导航（当前仅有指派给Ta）
            'assignedTo'    => "//*[@id='mainContent']/div/menu/li[1]",

            // 列选择器（与 dtable 的 data-col 对齐）
            'id'            => "//div[@data-col='id' and @data-row!='HEADER']",
            'name'          => "//div[@data-col='name' and @data-row!='HEADER']",
            'pri'           => "//div[@data-col='pri' and @data-row!='HEADER']",
            'status'        => "//div[@data-col='status' and @data-row!='HEADER']",
            'executionName' => "//div[@data-col='executionName' and @data-row!='HEADER']",
            'buildName'     => "//div[@data-col='buildName' and @data-row!='HEADER']",
            'begin'         => "//div[@data-col='begin' and @data-row!='HEADER']",
            'end'           => "//div[@data-col='end' and @data-row!='HEADER']",

            // 分页控件（table id 按模块约定）
            'pagerSizeMenu' => "//button[contains(@class, 'pager-size-menu')]",
            'sizePerPage'   => "//*[@class='menu-wrapper popup search-menu is-contextmenu']",
            'firstPage'     => "//*[@id='table-user-testtask']/div[3]/nav/a[1]",
            'prevPage'      => "//*[@id='table-user-testtask']/div[3]/nav/a[2]",
            'nextPage'      => "//*[@id='table-user-testtask']/div[3]/nav/a[3]",
            'lastPage'      => "//*[@id='table-user-testtask']/div[3]/nav/a[4]"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}