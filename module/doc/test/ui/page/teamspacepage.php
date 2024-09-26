<?php
class teamspacePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'createDocBtn' => '/html/body/div[1]/div/div[1]/div[2]/div/a',
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
