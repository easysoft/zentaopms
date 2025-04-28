<?php
class kanbanPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'settings'          => "//*[@id='navbar']//a[@data-id='settings']/span",
            'groupPickerInLite' => "//*[@id='actionBar']/button[1]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
