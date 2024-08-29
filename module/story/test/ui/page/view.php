<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'storyName'       => "//*[@id='mainContent']/div/div[1]/div[1]/span[2]",
            'status'          => "/html/body/div[1]/div/div/div/div[2]/div[2]/div[1]/div[2]/div[1]/div/div[6]/div[2]/span",
            'storyFrom'       => "//*[@id='sourceBox']",
            'historyOpenedBy' => "/html/body/div[1]/div/div/div/div[2]/div[1]/div[2]/div/div[2]/ul/li/div/div[2]/div/div/strong",
            'closeReason'     => "/html/body/div[1]/div/div/div/div[2]/div[2]/div[1]/div[2]/div[2]/div/div[6]/div[2]",
            'reviewer'        => "/html/body/div[1]/div/div/div/div[2]/div[2]/div[1]/div[2]/div[2]/div/div[3]/div[2]/div/span"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
