<?php
class todoPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $this->xpath = array(
            'userPicker'      => '//div[contains(@class, "picker-select-single")]',
            'all'             => "//*[@id='mainContent']/div/menu/li[1]",
            'before'          => "//*[@id='mainContent']/div/menu/li[2]",
            'future'          => "//*[@id='mainContent']/div/menu/li[3]",
            'thisWeek'        => "//*[@id='mainContent']/div/menu/li[4]",
            'thisMonth'       => "//*[@id='mainContent']/div/menu/li[5]",
            'thisYear'        => "//*[@id='mainContent']/div/menu/li[6]",
            'assignedToOther' => "//*[@id='mainContent']/div/menu/li[7]",
            'cycle'           => "//*[@id='mainContent']/div/menu/li[8]",
            'id'              => "//div[@data-col='id' and @data-row!='HEADER']",
            'name'            => "//div[@data-col='name' and @data-row!='HEADER']",
            'pri'             => "//div[@data-col='pri' and @data-row!='HEADER']",
            'date'            => "//div[@data-col='date' and @data-row!='HEADER']",
            'begin'           => "//div[@data-col='begin' and @data-row!='HEADER']",
            'end'             => "//div[@data-col='end' and @data-row!='HEADER']",
            'status'          => "//div[@data-col='status' and @data-row!='HEADER']",
            'type'            => "//div[@data-col='type' and @data-row!='HEADER']",
            'pagerSizeMenu'   => '//button[contains(@class, "pager-size-menu")]',
            'sizePerPage'     => '//*[@class="menu-wrapper popup search-menu is-contextmenu"]',
            'firstPage'       => '//*[@id="table-user-todo"]/div[3]/nav/a[1]',
            'prevPage'        => '//*[@id="table-user-todo"]/div[3]/nav/a[2]',
            'nextPage'        => '//*[@id="table-user-todo"]/div[3]/nav/a[3]',
            'lastPage'        => '//*[@id="table-user-todo"]/div[3]/nav/a[4]',
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}
