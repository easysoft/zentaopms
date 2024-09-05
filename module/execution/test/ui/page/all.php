<?php
class allPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'firstCheckbox' => "//*[@id='table-execution-all']/div[2]/div[1]/div/div[1]/div/div",
            'firstName'     => "//*[@id='table-execution-all']/div[2]/div[1]/div/div[2]/div/div/div/a",
            'firstBegin'    => "//*[@id='table-execution-all']/div[2]/div[2]/div/div[4]/div",
            'firstEnd'      => "//*[@id='table-execution-all']/div[2]/div[2]/div/div[5]/div",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
