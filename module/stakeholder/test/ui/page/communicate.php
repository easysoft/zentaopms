<?php
class communicatePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'communication' => "//*[@id='comment']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}