<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'save' => "//*[@id='zin_branch_create_form']/div[3]/div/button",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}

