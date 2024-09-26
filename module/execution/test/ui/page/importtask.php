<?php
class importTaskPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'pickInput'     => "//*[@data-page='execution-importtask']//div[@id='featureBar']/menu/div/div/div",
            'executionList' => "//*[@data-page='execution-importtask']/div[@class='pick-container']/div/menu/menu/li" ,
            'firstCheckbox' => "//*[@id='table-execution-importtask']/div[2]/div[1]/div/div[1]/div/div",
            'firstName'     => "//*[@id='table-execution-importtask']/div[2]/div[1]/div/div[2]/div/a",
            'num'           => "//*[@data-page='execution-importtask']/div[1]//li/a/span[2]",
            'saveBtn'       => "//*[@id='table-execution-importtask']/div[3]/nav[1]/button"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
