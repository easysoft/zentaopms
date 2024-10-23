<?php
class executionPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'name'       => "//*[@id='table-project-execution']/div[2]/div[1]/div/div[2]/div/div/div/a",
            'begin'      => "//*[@id='table-project-execution']/div[2]/div[2]/div/div[4]/div",
            'end'        => "//*[@id='table-project-execution']/div[2]/div[2]/div/div[5]/div",
            'subName'    => "//*[@id='table-project-execution']/div[2]/div[1]/div/div[4]/div/div[2]/div/a",
            'subBegin'   => "//*[@id='table-project-execution']/div[2]/div[2]/div/div[14]/div",
            'subEnd'     => "//*[@id='table-project-execution']/div[2]/div[2]/div/div[15]/div",
            'editBtn'    => "//*[@id='table-project-execution']/div[2]/div[3]/div/div/div/nav/a[3]/i",
            'submitBtn'  => "//*[@id='zin_programplan_edit_form']/div[9]/div/button/span",
            'planBegin'  => "//*[@id='table-project-execution']/div[2]/div[2]/div/div[4]/div",
            'planEnd'    => "//*[@id='table-project-execution']/div[2]/div[2]/div/div[5]/div",
            'addSprint'  => "//*[@id='actionBar']/a[2]/span",
            'sprintName' => "//*[@id='mainContent']/div/div/div[2]/div/div/div[2]/div/div/div/a",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
