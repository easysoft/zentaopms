<?php
class editPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'type'    => "//*[@id='zin_design_edit_1_form']/div[4]/div/div/input",
            'product' => "//*[@id='zin_design_edit_1_form']/div[2]/div/div/input",
            'nameTip' => "//*[@id='nameTip']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
