<?php
class batchCreatePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'submitBtn'      => "//*[@id='zin_stage_batchcreate_formBatch']/div[2]/button[1]/span",
            'percentOverTip' => "//*[@class='modal modal-async load-indicator modal-alert modal-trans show in']/div/div/div[2]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
