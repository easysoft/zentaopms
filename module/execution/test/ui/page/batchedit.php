<?php
class batchEditPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'submitBtn' => "//button[@type='submit']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
