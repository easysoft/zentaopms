<?php
class resetpasswordPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $this->xpath = array(
            'password1'    => "//*[@id='password1']",
            'password2'    => "//*[@id='password2' or @name='password2']",
            'submitBtn'    => "//button[@type='submit']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}