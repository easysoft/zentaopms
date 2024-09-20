<?php
class linkStoryPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
        'firstCheckbox' => "//*[@id='table-execution-linkstory']/div[2]/div/div/div[1]/div/div",
        'firstName'     => "//*[@id='table-execution-linkstory']/div[2]/div[1]/div/div[2]/div/a",
        'saveBtn'       => "//*[@id='table-execution-linkstory']/div/nav[1]/button",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
