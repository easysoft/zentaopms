<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'designName'    => "//*[@id='table-design-browse']/div[2]/div[1]/div/div[2]/div/a",
            'linkedProduct' => "//*[@id='table-design-browse']/div[2]/div[2]/div/div[1]/div",
            'designType'    => "//*[@id='table-design-browse']/div[2]/div[2]/div/div[2]/div/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
