<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'settings'      => "//*[@id='navbar']//a[@data-id='settings']/span",
            'editBtn'       => "//*[@id='table-project-browse']/div[2]/div[3]/div/div[1]/div/nav/a[2]/i",
            'projectName'   => "//*[@id='table-project-browse']/div[2]/div[1]/div/div[2]/div/a",
            'scrumName'     => "//*[@id='mainContent']/div[2]/div/div/div[2]/div/div/div[2]/div/a",
            'moreBtn'       => "//*[@id='table-project-browse']/div[2]/div[3]/div/div[1]/div/nav/button[1]/span",
            'closeBtn'      => "//*[@title='关闭项目']",
            'closeProject'  => "//span[text()='关闭项目']",
            'closed'        => "//*[@id='more']/menu/menu/li[2]/a/div",
            'activeBtn'     => "//*[@title='激活项目']",
            'activeProject' => "//span[text()='激活项目']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
