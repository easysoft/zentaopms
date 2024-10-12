<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'basic'          => "//*[@id='mainContent']/div[2]/div/div/div/div/ul/li[4]/a/span",
            'basicBuildName' => "//*[@class='section']/table/tbody[1]/tr[2]/td",
            'basicExecution' => "//*[@class='section']/table/tbody[1]/tr[3]/td",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
