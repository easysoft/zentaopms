<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'totalNum'       => "//*[@data-id='totalStatus']/span[2]",
            'firstID'        => "//*[@id='table-testtask-browse']/div[2]/div[1]/div/div[1]/div",
            'firstName'      => "//*[@id='table-testtask-browse']/div[2]/div[1]/div/div[2]/div/a",
            'firstDeleteBtn' => "//*[@id='table-testtask-browse']/div[2]/div[3]/div/div[1]/div/nav/a[last()]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
