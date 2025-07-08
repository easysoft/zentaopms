<?php
class changePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'srCommon' => "//*[@id='dataform']/div[1]/div/div[1]/div[1]/div[2]/div[1]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
