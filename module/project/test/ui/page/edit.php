<?php
class editPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'settings' => "//*[@id='navbar']/menu/li[14]/a/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
