<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'projectName'           => "//*[@id='mainContent']/div[1]/div[1]/div[1]/div[2]/div[1]/span[2]",
            'category'              => "//*[@id='mainContent']/div[1]/div[1]/div[1]/div[2]/div[1]/span[3]",
            'acl'                   => "//*[@id='mainContent']/div[1]/div[1]/div[1]/div[2]/div[1]/span[5]",
            'hasproductend'         => "//*[@id='mainContent']/div/div/div[2]/div/table[3]/tbody/tr/td/div/div[2]/span[2]",
            'noproductend'          => "//*[@id='mainContent']/div/div/div[2]/div/table[2]/tbody/tr/td/div/div[2]/span[2]",
            'waterfallend'          => "//*[@id='mainContent']/div[1]/div[1]/div[2]/div/table[3]/tbody/tr/td/div/div[2]/span[2]",
            'waterfallnoproductend' => "//*[@id='mainContent']/div[1]/div[1]/div[2]/div/table[2]/tbody/tr/td/div/div[2]/span[2]",
            'projectName'           => "//*[@id='mainContent']/div[1]/div[1]/div[1]/div[2]/div[1]/span[2]",
            'acl'                   => "//*[@id='mainContent']/div[1]/div[1]/div[1]/div[2]/div[1]/span[5]",
            'end'                   => "//*[@id='mainContent']/div[1]/div[1]/div[2]/div/table[3]/tbody/tr/td/div/div[2]/span[2]",
            'linkedProduct'         => "//*[@id='mainContent']/div[1]/div[1]/div[2]/div/table[1]/tbody/tr[3]/td[1]/div/div/a/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
