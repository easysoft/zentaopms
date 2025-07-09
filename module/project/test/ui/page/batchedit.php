<?php
class batchEditPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'settings'   => "//*[@id='navbar']/menu/li[14]/a/span",
            'alertModal' => "//*[@class='modal modal-async load-indicator modal-alert modal-trans show in']/div/div/div[2]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
