<?php
class storyPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $this->xpath = array(
            // 顶部用户选择器
            'userPicker'    => '//div[contains(@class, "picker-select-single")]',
            // 顶部子导航（AssignedTo/OpenedBy/ReviewedBy/ClosedBy）
            'assignedTo'    => "//*[@id='mainContent']/div/menu/li[1]",
            'openedBy'      => "//*[@id='mainContent']/div/menu/li[2]",
            'reviewedBy'    => "//*[@id='mainContent']/div/menu/li[3]",
            'closedBy'      => "//*[@id='mainContent']/div/menu/li[4]",
            // 列选择器（与 dtable 的 data-col 对齐）
            'id'            => "//div[@data-col='id' and @data-row!='HEADER']",
            'title'         => "//div[@data-col='title' and @data-row!='HEADER']",
            'pri'           => "//div[@data-col='pri' and @data-row!='HEADER']",
            'status'        => "//div[@data-col='status' and @data-row!='HEADER']",
            'productTitle'  => "//div[@data-col='productTitle' and @data-row!='HEADER']",
            'planTitle'     => "//div[@data-col='planTitle' and @data-row!='HEADER']",
            'openedByName'  => "//div[@data-col='openedBy' and @data-row!='HEADER']",
            'estimate'      => "//div[@data-col='estimate' and @data-row!='HEADER']",
            'stage'         => "//div[@data-col='stage' and @data-row!='HEADER']",
            // 分页控件（table id 按模块约定）
            'pagerSizeMenu' => '//button[contains(@class, "pager-size-menu")]',
            'sizePerPage'   => '//*[@class="menu-wrapper popup search-menu is-contextmenu"]',
            'firstPage'     => '//*[@id="table-user-story"]/div[3]/nav/a[1]',
            'prevPage'      => '//*[@id="table-user-story"]/div[3]/nav/a[2]',
            'nextPage'      => '//*[@id="table-user-story"]/div[3]/nav/a[3]',
            'lastPage'      => '//*[@id="table-user-story"]/div[3]/nav/a[4]'
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}