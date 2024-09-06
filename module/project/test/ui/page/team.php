<?php
class teamPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'browseFirAccount' => "//*[@id='table-project-team']/div[2]/div[1]/div/div/div/a",
            'browseSecAccount' => "//*[@id='table-project-team']/div[2]/div[1]/div/div[8]/div/a",
            'browseSecRole'    => "//*[@id='table-project-team']/div[2]/div/div/div[9]/div",
            'browseSecDay'     => "//*[@id='table-project-team']/div[2]/div/div/div[11]/div",
            'browseSecHours'   => "//*[@id='table-project-team']/div[2]/div/div/div[12]/div",
            'amount'           => "//*[@id='featureBar']/menu/li/a/span[2]",
            'teamBtn'          => "//*[@id='actionBar']/a/span",
            'confirmBtn'       => "//*[@class='modal-footer']/nav/button[1]/span",
            'unlinkBtn'        => "//*[@id='table-project-team']/div[2]/div[2]/div/div/div/nav/a/i",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
