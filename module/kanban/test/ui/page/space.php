<?php
class spacePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'spaceName' => '//*[@id="mainContent"]/div[1]/div[1]/div[1]'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
