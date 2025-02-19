<?php
class editPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'nameTip'     => "//*[@id='nameTip']",
            'beginTip'    => "//*[@id='beginTip']",
            'endTip'      => "//*[@id='endTip']",
            'submitBtn'   => "//*[@id='zin_programplan_edit_form']/div[9]/div/button/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
