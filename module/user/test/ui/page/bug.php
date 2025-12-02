<?php
class bugPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $this->xpath = array(
            // 顶部用户选择器
            'userPicker'     => '//div[contains(@class, "picker-select-single")]',
            // 顶部子导航（AssignedTo/OpenedBy/ResolvedBy/ClosedBy）
            'assignedTo'     => "//*[@id='mainContent']/div/menu/li[1]",
            'openedBy'       => "//*[@id='mainContent']/div/menu/li[2]",
            'resolvedBy'     => "//*[@id='mainContent']/div/menu/li[3]",
            'closedBy'       => "//*[@id='mainContent']/div/menu/li[4]",
            // 列选择器（与 dtable 的 data-col 对齐）
            'id'             => "//div[@data-col='id' and @data-row!='HEADER']",
            'title'          => "//div[@data-col='title' and @data-row!='HEADER']",
            'severity'       => "//div[@data-col='severity' and @data-row!='HEADER']//span",
            'pri'            => "//div[@data-col='pri' and @data-row!='HEADER']",
            'type'           => "//div[@data-col='type' and @data-row!='HEADER']",
            'openedByName'   => "//div[@data-col='openedBy' and @data-row!='HEADER']",
            'resolvedByName' => "//div[@data-col='resolvedBy' and @data-row!='HEADER']",
            'resolution'     => "//div[@data-col='resolution' and @data-row!='HEADER']",
            // 分页控件（table id 按模块约定）
            'pagerSizeMenu'  => '//button[contains(@class, "pager-size-menu")]',
            'sizePerPage'    => '//*[@class="menu-wrapper popup search-menu is-contextmenu"]',
            'firstPage'      => '//*[@id="table-user-bug"]/div[3]/nav/a[1]',
            'prevPage'       => '//*[@id="table-user-bug"]/div[3]/nav/a[2]',
            'nextPage'       => '//*[@id="table-user-bug"]/div[3]/nav/a[3]',
            'lastPage'       => '//*[@id="table-user-bug"]/div[3]/nav/a[4]'
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}