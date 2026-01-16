<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'settings'    => "//*[@id='navbar']/menu/li[14]/a/span",
            'deleteBtn_2' => "//*[@id='zin_programplan_create_formBatch']/div[1]/table/tbody/tr[2]/td[20]/button[4]",
            'deleteBtn_3' => "//*[@id='zin_programplan_create_formBatch']/div[1]/table/tbody/tr[3]/td[20]/button[4]",
            'deleteBtn_4' => "//*[@id='zin_programplan_create_formBatch']/div[1]/table/tbody/tr[4]/td[20]/button[4]",
            'deleteBtn_5' => "//*[@id='zin_programplan_create_formBatch']/div[1]/table/tbody/tr[5]/td[20]/button[4]",
            'deleteBtn_6' => "//*[@id='zin_programplan_create_formBatch']/div[1]/table/tbody/tr[6]/td[20]/button[4]",
            'begin'       => "//*[@id='begin_0']/div/input[2]",
            'end'         => "//*[@id='end_0']/div/input[2]",
            'submitBtn'   => "//*[@id='zin_programplan_create_formBatch']/div[2]/button",
            'beginTip'    => "//*[@id='begin[1]Tip']",
            'endTip'      => "//*[@id='end[1]Tip']",
            'nameTip'     => "//*[@class='modal modal-async load-indicator modal-alert modal-trans show in']/div/div/div[2]",
            'confirmBtn'  => "//*[@class='modal modal-async load-indicator modal-alert modal-trans show in']/div/div/div[3]/nav/button/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
