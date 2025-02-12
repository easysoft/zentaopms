<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'type' => '//*[@id="table-testsuite-browse"]/div[2]/div[1]/div/div[2]/div/div[1]/span',
            'name' => '//*[@id="table-testsuite-browse"]/div[2]/div[1]/div/div[2]/div/div[2]/a'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
