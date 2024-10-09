<?php
class testcasePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'num'           => "//*[@id='featureBar']/menu/li[1]/a/span[2]",
            'productNav'    => "//*[@id='pick-execution-menu']",
            'firstProduct'  => "(//div[@data-type='product'])[1]",
            'secondProduct' => "(//div[@data-type='product'])[2]",
            'thirdProduct'  => "(//div[@data-type='product'])[3]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
