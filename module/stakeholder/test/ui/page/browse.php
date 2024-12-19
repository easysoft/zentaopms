<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'title'       => "//*[@id='table-stakeholder-browse']/div[2]/div[1]/div/div[2]/div/a",
            'number'      => "//*[@id='table-stakeholder-browse']/div[3]/nav/div[1]",
            'communicate' => "//*[@id='table-stakeholder-browse']/div[2]/div[3]/div/div/div/nav/a[1]/i",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
