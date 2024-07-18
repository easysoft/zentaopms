<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'planTitle' => "//*[@id='planInfo']/table/tbody/tr[1]/td",
            'confirm'   => "//button[@z-key='confirm']",
            'status'    => "//*[@id='planInfo']/table/tbody/tr[last()-1]/td",
            'begin'     => "//*[@id='planInfo']/table/tbody/tr[2]/td",
            'end'       => "//*[@id='planInfo']/table/tbody/tr[3]/td",
            'parent'    => "//*[@id='planInfo']/table/tbody/tr[2]/td/a",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
