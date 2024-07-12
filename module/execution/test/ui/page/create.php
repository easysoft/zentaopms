<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'products'    => "//*[@name='products[0]']",
            'productsTip' => "//*[@id='products[0]Tip']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
