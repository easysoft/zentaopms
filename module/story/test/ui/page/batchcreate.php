<?php
class batchCreatePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'name'            => "/html/body/div[1]/div/div/div/div[2]/form/div[1]/table/tbody/tr[1]/td[8]/div/input",
            'reviewer'        => "/html/body/div[1]/div/div/div/div[2]/form/div[1]/table/tbody/tr[1]/td[16]/div/div/select",
            'storySave'       => "//*[@id='zin_story_batchcreate_formBatch']/div[2]/button[1]",
            'requirementSave' => "//*[@id='zin_requirement_batchcreate_formBatch']/div[2]/button[1]",
            'epicSave'        => "//*[@id='zin_epic_batchcreate_formBatch']/div[2]/button[1]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }


}
