<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'begin'     => "//*[@id='basicInfo']/table/tbody/tr[7]/td",
            'status'    => "//*[@id='basicInfo']/table/tbody/tr[10]/td",
            'submitBtn' => "//button[@type='submit']"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
