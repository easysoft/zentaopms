<?php
class bugPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 标签 */
            'allTab'         => "//*[@id='featureBar']/menu/li[1]/a",
            'unresolvedTab'  => "//*[@id='featureBar']/menu/li[2]/a",
            'bugNum'         => "//*[@id='table-project-bug']/div[3]/div[2]/strong",
            /* 1.5级产品导航 */
            'dropMenu'       => "//*[@id='pick-project-menu']",
            'firstProduct'   => "//*[@id='pick-pop-project-menu']/div[2]/div/div[1]/menu/li[2]/div",
            'secondProduct'  => "//*[@id='pick-pop-project-menu']/div[2]/div/div[1]/menu/li[3]/div",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
