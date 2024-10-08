<?php
class productspacePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'createDocBtn'   => '/html/body/div[1]/div/div[1]/div[2]/div/a',
            'saveBtn'        => '/html/body/div[1]/div/div/div[2]/form/div[1]/div[1]/div/div/a',
            'releaseBtn'     => '/html/body/div[1]/div/div/div[2]/form/div[2]/div/div/div[3]/div[10]/div/button',
            'leftListHeader' => '/html/body/div[1]/div/div/div[1]/div[1]/div[1]/button/span'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
