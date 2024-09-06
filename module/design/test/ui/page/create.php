<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'type'    => "//*[@id='zin_design_create_picker_type']/div/input",
            'product' => "//*[@id='zin_design_create_formGroup']/div/div/input",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
