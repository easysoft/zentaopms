<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'productName'   => "//*[@id='mainContent']/div[1]/div[1]/div[1]/div[2]/div[1]/div",
            'type'          => "//*[@id='mainContent']/div[1]/div[1]/div[1]/div[2]/div[1]/span[2]",
            'acl'           => "//*[@id='mainContent']/div[1]/div[1]/div[1]/div[2]/div[1]/div[2]",
            'confirmBtn'    => "/html/body/div[2]/div/div/div[3]/div[1]/div/form/div[2]/div/button",
            'status'        => "//*[@id='mainContent']/div[1]/div[1]/div[1]/div[2]/div[1]/span[last()]",
            'delStatus'     => "//*[@id='mainContent']/div[1]/div[1]/div[1]/div[2]/div[1]/span[last()-1]",
            'editBtn'       => "//*[@id='mainContent']/div[2]/div/a[last()-1]",
            'delBtn'        => "//*[@id='mainContent']/div[2]/div/a[last()]",
            'delConfirmBtn' => "//button[@z-key='confirm']"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
