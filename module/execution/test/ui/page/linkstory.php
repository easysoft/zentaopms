<?php
class linkStoryPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
        'firstCheckbox' => "//*[@id='table-execution-linkstory']/div[2]/div/div/div[1]/div/div",
        'saveBtn'       => "//*[@id='table-execution-linkstory']/div/nav[1]/button",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
