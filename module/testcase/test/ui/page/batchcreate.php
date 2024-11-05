<?php
class batchcreatePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'caseNameList' => "//div[@id='table-testcase-browse_table']//div[@data-col='title']//a[@data-app='qa']",
            'saveButton' => "//button[@type = 'submit']"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
