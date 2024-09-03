<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'basic'            => "//*[@id='mainContent']/div[2]/div/div/div/div/ul/li[4]/a/span",
            'basicreleasename' => "//*[@id='releaseInfo']/div[2]/div[1]/table/tbody/tr[2]/td",
            'basicstatus'      => "//*[@id='releaseInfo']/div[2]/div[1]/table/tbody/tr[4]/td",
            'basicplandate'    => "//*[@id='releaseInfo']/div[2]/div[1]/table/tbody/tr[5]/td",
            'basicreleasedate' => "//*[@id='releaseInfo']/div[2]/div[1]/table/tbody/tr[6]/td",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
