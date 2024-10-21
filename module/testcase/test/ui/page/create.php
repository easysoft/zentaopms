<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'caseNameList' => "//a[contains(text(),'testcase')]",
            'saveButton' => "//button[@type = 'submit']"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
