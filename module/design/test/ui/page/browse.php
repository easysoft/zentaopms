<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'designName'    => "//*[@id='table-design-browse']/div[2]/div[1]/div/div[2]/div/a",
            'linkedProduct' => "//*[@id='table-design-browse']/div[2]/div[2]/div/div[1]/div",
            'designType'    => "//*[@id='table-design-browse']/div[2]/div[2]/div/div[2]/div/span",
            'assignedTo'    => "//*[@name='assignedTo']",
            'assignedToBtn' => "//*[@class='form load-indicator form-ajax no-morph form-horz']/div[3]/div/button/span",
            'assigned'      => "//*[@id='table-design-browse']/div[2]/div[2]/div/div[3]/div/a/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
