<?php
class batchRunPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'modalText' => "//*[@class='modal-content']/div[2]/text()",
            'pass'      => "//*[@id='zin_testtask_batchrun_form']/table/tbody/tr[1]/td[5]/div/div[1]/label",
            'fail'      => "//*[@id='zin_testtask_batchrun_form']/table/tbody/tr[1]/td[5]/div/div[2]/label",
            'blocked'   => "//*[@id='zin_testtask_batchrun_form']/table/tbody/tr[1]/td[5]/div/div[3]/label",
            'submitBtn' => "//*[@id='zin_testtask_batchrun_form']/div/div/button[1]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
