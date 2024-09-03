<?php
class managemembersPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'account' => "//div[@id='teamForm']//input[@name='account[2]']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
