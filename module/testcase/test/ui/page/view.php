<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'caseNameView' => "(//div[@id='mainContent']//span[text()])[3]"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
