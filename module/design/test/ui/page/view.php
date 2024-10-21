<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'designName'    => "//*[@id='mainContent']/div[1]/div/div/span[2]",
            'designType'    => "//*[@id='mainContent']/div[2]/div[2]/div/div[2]/table/tbody/tr[1]/td",
            'linkedProduct' => "//*[@id='mainContent']/div[2]/div[2]/div/div[2]/table/tbody/tr[2]/td",
            'deleteBtn'     => "//*[@id='mainContent']/div[2]/div[1]/div[3]/div/a[4]/i",
            'confirmBtn'    => "//*[@class='modal modal-async load-indicator modal-alert modal-trans show in']/div/div/div[3]/nav/button[1]/span",
            'deleteFlag'    => "//*[@id='mainContent']/div[1]/div/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
