<?php
class batchCreatePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'type'    => "//*[@id='type_0']/div/input",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
