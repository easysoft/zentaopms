<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'storyName'   => "//*[@id='mainContent']/div/div[1]/div[1]/span[2]",
            'storyFrom'   => "//*[@id='sourceBox']",
            'status'      => "/html/body/div[1]/div/div/div/div[2]/div[2]/div[1]/div[2]/div[1]/div/div[6]/div[2]/span",
            'openedBy'    => "/html/body/div[1]/div/div/div/div[2]/div[2]/div[1]/div[2]/div[2]/div/div[1]/div[2]",
            'assignToONE' => "/html/body/div/div/div/div/div[2]/div[2]/div[1]/div[2]/div[2]/div/div[2]/div[2]",
            'reviewer'    => "/html/body/div[1]/div/div/div[1]/div[2]/div[2]/div[1]/div[2]/div[2]/div/div[3]/div[2]/div/span",
            'closeReason' => "/html/body/div[1]/div/div/div/div[2]/div[2]/div[1]/div[2]/div[2]/div/div[6]/div[2]",
            'activate'    => "//*[@type='submit']",
            'assignToBtn' => "//*[@type='submit']"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
