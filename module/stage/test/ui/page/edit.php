<?php
class editPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'submitBtn'     => "//*[@id='zin_stage_edit_1_form']/div[4]/div/button/span",
            'plusSubmitBtn' => "//*[@id='zin_stage_edit_7_form']/div[4]/div/button",
            'percentOverTip' => "//*[@id='percentTip']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
