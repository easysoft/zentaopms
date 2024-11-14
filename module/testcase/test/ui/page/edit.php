<?php
class editPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'caseName' => "(//div[@id='mainContent']//span[text()])[3]",
            'saveButton' => "//button[@type = 'submit']"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
