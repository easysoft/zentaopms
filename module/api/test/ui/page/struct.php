<?php
class structPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'createStructBtn' => '/html/body/div[1]/div/div[1]/div[2]/a',
            'fstStructName'   => '/html/body/div[1]/div/div[2]/div/div/div[2]/div[1]/div/div[3]/div'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
