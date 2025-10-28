<?php
class taskPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $this->xpath = array(
            'userPicker'    => '//div[contains(@class, "picker-select-single")]',
            'assignedTo'    => "//*[@id='mainContent']/div/menu/li[1]",
            'openedBy'      => "//*[@id='mainContent']/div/menu/li[2]",
            'finishedBy'    => "//*[@id='mainContent']/div/menu/li[3]",
            'involvedIn'    => "//*[@id='mainContent']/div/menu/li[4]",
            'closedBy'      => "//*[@id='mainContent']/div/menu/li[5]",
            'canceledBy'    => "//*[@id='mainContent']/div/menu/li[6]",
            'id'            => "//div[@data-col='id' and @data-row!='HEADER']",
            'name'          => "//div[@data-col='name' and @data-row!='HEADER']",
            'pri'           => "//div[@data-col='pri' and @data-row!='HEADER']",
            'status'        => "//div[@data-col='status' and @data-row!='HEADER']",
            'executionName' => "//div[@data-col='executionName' and @data-row!='HEADER']",
            'deadline'      => "//div[@data-col='deadline' and @data-row!='HEADER']",
            'estimate'      => "//div[@data-col='estimateLabel' and @data-row!='HEADER']",
            'consumed'      => "//div[@data-col='consumedLabel' and @data-row!='HEADER']",
            'left'          => "//div[@data-col='leftLabel' and @data-row!='HEADER']",
            'pagerSizeMenu' => '//button[contains(@class, "pager-size-menu")]',
            'sizePerPage'   => '//*[@class="menu-wrapper popup search-menu is-contextmenu"]',
            'firstPage'     => '//*[@id="table-user-task"]/div[3]/nav/a[1]',
            'prevPage'      => '//*[@id="table-user-task"]/div[3]/nav/a[2]',
            'nextPage'      => '//*[@id="table-user-task"]/div[3]/nav/a[3]',
            'lastPage'      => '//*[@id="table-user-task"]/div[3]/nav/a[4]',
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}
