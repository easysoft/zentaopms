<?php
class changePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'srCommon' => "//div[@data-name='title']/label[contains(@class,'form-label')]",
            /*运营界面*/
            'reviewerAdmin' => "//*[@class='pick-container']//menu//a/div/div[text()='admin']"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
