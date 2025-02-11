<?php
class batchCreatePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'name'            => "//*[@id='title_0']",
            'reviewer'        => "/html/body/div[1]/div/div/div/div[2]/form/div[1]/table/tbody/tr[1]/td[16]/div/div/select",
            'requirementSave' => "//*[@id='zin_requirement_batchcreate_formBatch']/div[2]/button[1]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
