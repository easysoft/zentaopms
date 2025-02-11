<?php
class batchCreatePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'selectDept' => "//*[@name='dept']",
            'importBtn'  => "//*[@id='mainContent']/div/div[1]/a/span",
            'deleteBtn'  => "//*[@id='zin_stakeholder_batchcreate_formBatch']/div[1]/table/tbody/tr[1]/td[2]/button[2]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
