<?php
class testcasePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $this->xpath = array(
            'userPicker'    => '//div[contains(@class, "picker-select-single")]',
            'assignedTo'    => "//*[@id='mainContent']/div/menu/li[1]",
            'createdBy'     => "//*[@id='mainContent']/div/menu/li[2]",
            'id'            => "//div[@data-col='caseID' and @data-row!='HEADER']",
            'title'         => "//div[@data-col='title' and @data-row!='HEADER']",
            'pri'           => "//div[@data-col='pri' and @data-row!='HEADER']",
            'type'          => "//div[@data-col='type' and @data-row!='HEADER']",
            'status'        => "//div[@data-col='status' and @data-row!='HEADER']",
            'openedBy'      => "//div[@data-col='openedBy' and @data-row!='HEADER']",
            'lastRunner'    => "//div[@data-col='lastRunner' and @data-row!='HEADER']",
            'lastRunDate'   => "//div[@data-col='lastRunDate' and @data-row!='HEADER']",
            'lastRunResult' => "//div[@data-col='lastRunResult' and @data-row!='HEADER']",
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}
