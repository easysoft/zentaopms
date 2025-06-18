<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'savebtn'  => "//*[@id='form-task-create']/div[27]/button[1]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
