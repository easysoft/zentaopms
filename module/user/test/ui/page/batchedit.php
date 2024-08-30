<?php
class batchEditPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'realname'      => "//*[@id='realname_0']",
            'passwordfield' => "//*[@id='passwordfield_0']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
