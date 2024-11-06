<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'caseListID'   => "//a[text()='ID']",
            'caseName'     => "(//div[@data-col='title']//a[@data-app='qa'])[1]",
            'caseNameView' => "(//div[@id='mainContent']//span[text()])[3]",
            'review'       => "(//div[@data-row='case_1']//a[@data-toggle='modal'])[1]",
            'comment'      => "//zen-editor[@menubar-mode='compact']"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
