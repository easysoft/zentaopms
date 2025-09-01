<?php
class createScenePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'downBtn'      => "//*[@id='actionBar']/div[1]/button/span",
            'manageModule' => "//*[@id='moduleIdBox']/div[2]/a[1]/span",
            'module1'      => "(//*[@id='modules[]'])[1]",
            'module2'      => "(//*[@id='modules[]'])[2]",
            'saveBtn'      => "//*[@id='zin_testcase_browse_form']/div[6]/div/button/span",
            'module'       => "//*[@id='moduleIdBox']/div/div/span[1]",
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
