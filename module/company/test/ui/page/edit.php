<?php
class editPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'editbtn'  => "//*[@id='mainContent']/div[1]/a",
            'primary'  => "//*[@id='guest1']",
            'primary0' => "//*[@id='guest0']",
            'savebtn'  => "//*[@id='zin_company_edit_1_form']/div[9]/div/button"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
