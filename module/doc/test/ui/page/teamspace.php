<?php
class teamspacePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'createLibBtn' => '/html/body/div[1]/div/div[1]/div[2]/div/a',
            'createDocBtn' => '/html/body/div[1]/div/div[1]/div[2]/div[2]/a',
            'saveBtn'      => '/html/body/div[1]/div/div/div[2]/form/div[1]/div[1]/div/div/a',
            'releaseBtn'   => '/html/body/div[1]/div/div/div[2]/form/div[2]/div/div/div[3]/div[10]/div/button',
            'fstDocName'   => '/html/body/div[1]/div/div[2]/div[2]/div/div/div[2]/div[1]/div/div[2]/div/a'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
