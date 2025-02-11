<?php
class expectPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'expectComment' => "//*[@id='zin_stakeholder_expect_form']/div[1]/div/zen-editor",
            'progress'      => "//*[@id='zin_stakeholder_expect_form']/div[2]/div/zen-editor",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
