<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'begin'     => "//*[@id='basicInfo']/table/tbody/tr[7]/td",
            'status'    => "//*[@id='basicInfo']/table/tbody/tr[10]/td",
            'submitBtn' => "//button[@type='submit']",
            'buttons'   => "//*[@id='mainContent']/div[2]/div[1]/div[3]/div"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
