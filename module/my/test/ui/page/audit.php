<?php
class auditPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'allTab'    => '//*[@id="featureBar"]/menu/li[1]/a',
            'SRTab'     => '//*[@id="featureBar"]/menu/li[2]/a',
            'ERTab'     => '//*[@id="featureBar"]/menu/li[3]/a',
            'URTab'     => '//*[@id="featureBar"]/menu/li[4]/a',
            'allNum'    => '//*[@id="featureBar"]/menu/li[1]/a/span[2]',
            'SRNum'     => '//*[@id="featureBar"]/menu/li[2]/a/span[2]',
            'ERNum'     => '//*[@id="featureBar"]/menu/li[3]/a/span[2]',
            'URNum'     => '//*[@id="featureBar"]/menu/li[4]/a/span[2]',
            'reviewBtn' => '//*[@id="table-my-audit"]/div[2]/div[3]/div/div/div/nav/a'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
