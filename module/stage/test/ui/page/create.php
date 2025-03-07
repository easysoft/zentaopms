<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'submitBtn'      => "//*[@id='zin_stage_create_form']/div[3]/div/button",
            'percentOverTip' => "//*[@id='percentTip']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
