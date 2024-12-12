<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'name' => "//*[@id='basicInfo']/table/tbody/tr[1]/td",
            'type' => "//*[@id='basicInfo']/table/tbody/tr[9]/td",
            'key'  => "//*[@id='basicInfo']/table/tbody/tr[8]/td",

        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
