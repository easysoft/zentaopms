<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'passwdCopy' => "//*[@id='password']/following-sibling::button",
            'tokenCopy'  => "//*[@id='token']/following-sibling::button"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
