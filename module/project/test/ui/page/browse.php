<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'settings'       => "//*[@id='navbar']//a[@data-id='settings']/span",
            'editBtn'        => "//*[@id='table-project-browse']/div[2]/div[3]/div/div[1]/div/nav/a[2]/i",
            'projectName'    => "//*[@id='table-project-browse']/div[2]/div[1]/div/div[2]/div/a",
            'scrumName'      => "//*[@id='mainContent']/div[2]/div/div/div[2]/div/div/div[2]/div/a",
            'moreBtn'        => "//*[@id='table-project-browse']/div[2]/div[3]/div/div[1]/div/nav/button[1]/span",
            'closeBtn'       => "//*[@class='icon item-icon icon-off']",
            'closeProject'   => "//*[@class='modal modal-async load-indicator modal-trans show in']/div/div/div[3]/div/div/form/div[3]/div/button",
            'closed'         => "//*[@id='more']/menu/menu/li[2]/a/div",
            'activeBtn'      => "//*[@id='table-project-browse']/div[2]/div[3]/div/div/div/nav/a[1]/i",
            'activeProject'  => "//*[@class='modal modal-async load-indicator modal-trans show in']/div/div/div[3]/div/div/form/div[3]/div/button",
            'startBtn'       => "//*[@id='table-project-browse']/div[2]/div[3]/div/div/div/nav/a",
            'startProject'   => "//*[@class='modal-content']/div[3]/div/div/form/div[3]/div/button/span",
            'suspendBtn'     => "//*[@class='popover show fade dropdown in']/menu/menu/li/a",
            'suspendProject' => "//*[@class='modal-content']/div[3]/div/div/form/div[2]/div/button",
            'browseStatus'   => "//*[@id='table-project-browse']/div[2]/div[2]/div/div/div/span",
            'selectBtn'      => "//*[@id='table-project-browse']/div[2]/div[1]/div/div[1]",
            'batchEditBtn'   => "//*[@id='table-project-browse']/div[3]/nav[1]/nav/button/span",
            'begin'          => "//*[@id='table-project-browse']/div[2]/div[2]/div/div[7]/div",
            'end'            => "//*[@id='table-project-browse']/div[2]/div[2]/div/div[8]/div",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
