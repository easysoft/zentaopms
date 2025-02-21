<?php
class executionPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'name'       => "//*[@id='table-project-execution']/div[2]/div[1]/div/div[2]/div/a",
            'begin'      => "//*[@id='table-project-execution']/div[2]/div[2]/div/div[5]/div",
            'end'        => "//*[@id='table-project-execution']/div[2]/div[2]/div/div[6]/div",
            'subName'    => "//*[@id='table-project-execution']/div[2]/div[1]/div/div[4]/div/a[1]",
            'subBegin'   => "//*[@id='table-project-execution']/div[2]/div[2]/div/div[18]/div",
            'subEnd'     => "//*[@id='table-project-execution']/div[2]/div[2]/div/div[19]/div",
            'editBtn'    => "//*[@id='table-project-execution']/div[2]/div[3]/div/div/div/nav/a[3]/i",
            'submitBtn'  => "//*[@id='zin_programplan_edit_form']/div[9]/div/button/span",
            'addSprint'  => "//*[@id='actionBar']/a[2]/span",
            'sprintName' => "//*[@id='table-project-execution']/div[2]/div[1]/div/div[2]/div/a",
            'planEnd'    => "//*[@id='table-project-execution']/div[2]/div[2]/div/div[6]/div",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
