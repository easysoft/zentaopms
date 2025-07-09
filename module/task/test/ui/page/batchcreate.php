<?php
class batchCreatePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'name'       => "//*[@id='name_0']",
            'assignedTo' => "//*[@name='assignedTo[1]']",
            'estimate'   => "//*[@id='estimate_0']",
            'savebtn'    => "//*[@id='zin_task_batchcreate_formBatch']/div[2]/button[1]/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
