<?php
class managePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'branchName' => "//*[@id='zin_branch_create_form']/div[1]/input",
            'branchDesc' => "//*[@id='zin_branch_create_form']/div[2]/textarea",
            'save'       => "//*[@id='zin_branch_create_form']/div[3]/div/button",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}

