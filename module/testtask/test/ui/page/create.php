<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'createBuildBtn' => "//span[@class='input-group-addon']/a[@id='buildCreateLink']",
            'refreshBtn'     => "//span[@class='input-group-addon']/a[@class='refresh']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
