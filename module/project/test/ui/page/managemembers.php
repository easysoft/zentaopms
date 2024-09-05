<?php
class manageMembersPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'account'        => "//*[@id='account1']/div/span[1]",
            'firstDeleteBtn' => "//*[@id='teamForm']/table/tbody/tr[1]/td[6]/div/button[2]/i",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
