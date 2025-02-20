<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'settings'  => "//*[@id='navbar']/menu/li[14]/a/span",
            'noproduct' => "//*[@id='mainContent']/div/div[2]/div/form/div[2]/div/div[2]/label",
            'tip'       => "//*[@id='mainContent']/div/div[2]/div/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
