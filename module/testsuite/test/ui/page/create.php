<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'saveBtn' => '//*[@id="testsuiteCreateForm"]/div[2]//div[4]//button[1]'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
