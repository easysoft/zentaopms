<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'editBtn' => "//*[@class='toolbar']/a[4]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
