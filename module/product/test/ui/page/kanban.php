<?php
class kanbanPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'planNum'      => '//*[@id="mainContent"]/div/div/div[2]/div[1]/div[2]/div[1]/div/div/span[2]/div/span',
            'projectNum'   => '//*[@id="mainContent"]/div/div/div[2]/div[1]/div[2]/div[2]/div[2]/div[1]/div/div/span[2]/div/span',
            'executionNum' => '//*[@id="mainContent"]/div/div/div[2]/div[1]/div[2]/div[2]/div[2]/div[2]/div/div/span[2]/div/span',
            'releaseNum'   => '//*[@id="mainContent"]/div/div/div[2]/div[1]/div[2]/div[3]/div/div/span[2]/div/span'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
