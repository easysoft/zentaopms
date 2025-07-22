<?php
class changePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'srCommon' => "//*[@id='dataform']/div[1]/div/div[1]/div[1]/div[2]/div[1]",
            /*运营界面*/
            'reviewerAdmin' => "//*[@class='pick-container']//menu//a/div/div[text()='admin']"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
