<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'user' => "//*[@name='user']",
            'typecompany' => "//*[@id='fromcompany']",
            'typeoutside' => "//*[@id='fromoutside']",
            'key'         => "//*[@id='key1']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
