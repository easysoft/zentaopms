<?php
class editPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'key'            => "//*[@id='zin_stakeholder_edit_1_form']/div[1]/div/div[2]/label",
            'personality'    => "//*[@id='zin_stakeholder_edit_1_form']/div[2]/div/zen-editor",
            'impactAnalysis' => "//*[@id='zin_stakeholder_edit_1_form']/div[3]/div/zen-editor",
            'response'       => "//*[@id='zin_stakeholder_edit_1_form']/div[4]/div/zen-editor",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
