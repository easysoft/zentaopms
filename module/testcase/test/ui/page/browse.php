<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'caseListID' => "//a[text()='ID']"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
