<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'designName'    => "//*[@id='table-design-browse']/div[2]/div[1]/div/div[2]/div/a",
            'linkedProduct' => "//*[@id='mainContent']/div[2]/div[2]/div/div[2]/table/tbody/tr[2]/td",
            'designType'    => "//*[@id='table-design-browse']/div[2]/div[2]/div/div[2]/div/span",
            'product'       => "//*[@id='table-design-browse']/div[2]/div[2]/div/div[1]/div",
            'assignedTo'    => "//*[@name='assignedTo']",
            'assignedToBtn' => "//*[@class='form load-indicator form-ajax no-morph form-horz']/div[3]/div/button/span",
            'assigned'      => "//*[@id='table-design-browse']/div[2]/div[2]/div/div[3]/div/a/span",
            'allMenu'       => "//*[@id='mainNavbar']/div/menu/li[1]/a/span",
            'hldsMenu'      => "//*[@id='mainNavbar']/div/menu/li[2]/a/span",
            'ddsMenu'       => "//*[@id='mainNavbar']/div/menu/li[3]/a/span",
            'dbdsMenu'      => "//*[@id='mainNavbar']/div/menu/li[4]/a/span",
            'adsMenu'       => "//*[@id='mainNavbar']/div/menu/li[5]/a/span",
            'designNum'     => "//*[@id='table-design-browse']/div[3]/nav/div[1]",
            'dropMenu'      => "//*[@id='pick-design-menu']",
            'firstProduct'  => "//*[@id='pick-pop-design-menu']/div[2]/div/div/menu/li[2]/div/div/div",
            'secondProduct' => "//*[@id='pick-pop-design-menu']/div[2]/div/div/menu/li[3]/div/div/div",
            'viewCommitBtn' => "//*[@id='table-design-browse']/div[2]/div[3]/div/div[4]/div/nav/a[2]",
            'commitNum'     => "//*[@id='table-design-viewcommit']/div[3]/nav/div[1]",
            'closeBtn'      => "//*[@id='viewCommitModal']/div/div/div[2]/button",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
