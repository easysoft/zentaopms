<?php
class denyPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $this->xpath = array(
            'denyBox'      => "//*[@id='denyBox']",
            'denyHeader'   => "//*[@id='denyBox']/div[1]/div",
            'denyAlert'    => "//*[@id='denyBox']//div[contains(@class,'alert')]",
            'denyAlertMsg' => "//*[@id='denyBox']//div[contains(@class,'alert')]/div",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}