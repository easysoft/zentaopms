<?php
class indexPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'defaultProgram' => '/html/body/div/div/div/div[2]/div/div/div[3]/form/div[2]/div/div/span[1]',
            'systemSetting'  => '/html/body/div/div/div/div[1]/div[1]/div[2]/div[1]/div/h4/div',
            'useLight'       => '//*[@id="useLight"]',
            'comfirmBtn'     => '//*[@class="m-custom-mode"]/div[2]/div[1]/div[1]/div[3]/nav[1]/button[1]'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
