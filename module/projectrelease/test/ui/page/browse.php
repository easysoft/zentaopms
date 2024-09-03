<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'editBtn' => "//*[@id='table-projectrelease-browse']/div[2]/div[3]/div/div/div/nav/a[4]/i",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
