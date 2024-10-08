<?php
class manageProductsPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'linkBtn'       => "//*[@id='manageProducts']/div[1]/div[2]/div[1]/a/p",
            'otherProducts' => "//*[@class='form-group-wrapper picker-box']/div/select",
            'checkbox'      => "//*[@id='manageProducts']/div[1]/div[1]/div[2]/button[3]/div/label",
            'saveBtn'       => "//*[@id='manageProducts']/div[2]/div/button/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
