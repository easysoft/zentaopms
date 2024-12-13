<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'totalNum' => "//*[@data-id='totalStatus']/span[2]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
