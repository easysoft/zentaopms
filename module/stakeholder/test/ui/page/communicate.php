<?php
class communicatePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'communication' => "//*[@id='comment']",
            'submitBtn'     => "//*[@id='zin_stakeholder_communicate_form']/div[2]/div/button",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
