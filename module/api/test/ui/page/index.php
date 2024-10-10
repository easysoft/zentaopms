<?php
class indexPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'createLibBtn' => '/html/body/div[1]/div/div[1]/div[2]/a[4]',
            'createApiBtn' => '/html/body/div[1]/div/div[1]/div[2]/a[5]',
            'fstDocPath'   => '/html/body/div[1]/div/div[2]/div[2]/div/div/div[2]/ul/li/div/a/span[2]',
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
