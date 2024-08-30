<?php
class projectViewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'allTab'     => '//*[@id="featureBar"]/menu/li[1]/a[1]',
            'fstProgram' => '/html/body/div/div/div[2]/div/div/div[2]/div[1]/div/div[2]/div[1]/a',
            'addProject' => '/html/body/div/div/div[1]/div[2]/a[1]',
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
