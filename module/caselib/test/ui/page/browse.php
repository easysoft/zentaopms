<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'viewName'     => "//*[@class='modal-content']/div[2]/div/div/div/span[2]",
            'viewLibModal' => "//*[@id='actionBar']/a[1]",
            'editBtn'      => "//*[@id='viewLibModal']/div/div/div[2]/div[2]/div/div[3]/div/a[1]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
