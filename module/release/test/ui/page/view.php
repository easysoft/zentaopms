<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'releaseInfo'    => '//*[@id='releaseTabs']/div[1]/ul/li[4]/a',
            'releasedStatus' => '//*[@id='releaseInfo']/div[2]/div[1]/table/tbody/tr[4]/td',
            'planedDate'     => '//*[@id='releaseInfo']/div[2]/div[1]/table/tbody/tr[5]/td',
            'releasedDate'   => '//*[@id='releaseInfo']/div[2]/div[1]/table/tbody/tr[6]/td'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
