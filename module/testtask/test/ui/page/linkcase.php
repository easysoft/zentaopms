<?php
class linkCasePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'num'      => "//*[@id='table-testtask-linkcase']/div[3]/div[2]/strong",
            'checkbox' => "//*[@id='table-testtask-linkcase']/div[2]/div[1]/div/div[1]/div/div",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
