<?php
class deletePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $this->xpath = array(
            'verifyPassword'    => "//*[@id='verifyPassword']|//*[@name='verifyPassword']",
            'verifyPasswordTip' => "//div[@id='verifyPasswordTip']",
            'submitBtn'         => "//*[@id='ajaxModal']//button[@type='submit']|//button[@type='submit']",
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}