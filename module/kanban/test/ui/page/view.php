<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'createBtn'   => '//*[@id="kanbanList"]/div/div/div/div[2]/div[1]/div[2]/div[1]/div/nav/button[1]',
            'firCardName' => '//*[@id="kanbanList"]/div/div/div/div[2]/div[2]/div/div[2]/div[1]/div/div/div/div/div[1]/a',
            'moreBtn'     => '//*[@id="kanbanList"]/div/div/div/div[2]/div[2]/div/div[2]/div[1]/div/div/div[1]/div/nav/button'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
