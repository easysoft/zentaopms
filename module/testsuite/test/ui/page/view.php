<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'name' => '//*[@id="mainContent"]/div[1]/div[1]/div/span[2]'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
