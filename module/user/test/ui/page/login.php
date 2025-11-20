<?php
class loginPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $this->xpath = array(
            'avatar'   => '//*[@id="userMenu-toggle"]/div/div',
            'account'  => "//*[@id='account']",
            'password' => "//*[@id='password']",
            'submit'   => "//*[@id='submit']",
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}