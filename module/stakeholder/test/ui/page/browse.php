<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'title'       => "//*[@id='table-stakeholder-browse']/div[2]/div[1]/div/div[2]/div/a",
            'type'        => "//*[@id='table-stakeholder-browse']/div[2]/div[2]/div/div[1]/div",
            'number'      => "//*[@id='table-stakeholder-browse']/div[3]/nav/div[1]",
            'communicate' => "//*[@id='table-stakeholder-browse']/div[2]/div[3]/div/div/div/nav/a[1]/i",
            'expect'      => "//*[@id='table-stakeholder-browse']/div[2]/div[3]/div/div/div/nav/a[2]/i",
            'deleteBtn'   => "//*[@id='table-stakeholder-browse']/div[2]/div[3]/div/div/div/nav/a[4]/i",
            'confirmBtn'  => "//*[@class='modal modal-async load-indicator modal-alert modal-trans show in']/div/div/div[3]/nav/button[1]/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
