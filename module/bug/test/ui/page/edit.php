<?php
class editPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'save' => "//*[@data-page='bug-edit']//form/div[2]/button"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
