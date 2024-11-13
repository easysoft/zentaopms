<?php
class projectPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'linkBtn' => '//*[@id="actionBar"]/a[1]',
            /*tab标签*/
            'allTab'        => '//*[@id="featureBar"]/menu/li[1]/a',
            'unfinishedTab' => '//*[@id="featureBar"]/menu/li[2]/a',
            'waitingTab'    => '//*[@id="featureBar"]/menu/li[3]/a',
            'doingTab'      => '//*[@id="featureBar"]/menu/li[4]/a',
            'suspendedTab'  => '//*[@id="featureBar"]/menu/li[5]/a',
            'closedTab'     => '//*[@id="featureBar"]/menu/li[6]/a',
            /*tab标签下项目数*/
            'allNum'        => '//*[@id="featureBar"]/menu/li[1]/a/span[2]',
            'unfinishedNum' => '//*[@id="featureBar"]/menu/li[2]/a/span[2]',
            'waitingNum'    => '//*[@id="featureBar"]/menu/li[3]/a/span[2]',
            'doingNum'      => '//*[@id="featureBar"]/menu/li[4]/a/span[2]',
            'suspendedNum'  => '//*[@id="featureBar"]/menu/li[5]/a/span[2]',
            'closedNum'     => '//*[@id="featureBar"]/menu/li[6]/a/span[2]',
            'firstProject'  => '//*[@id="table-product-project"]/div[2]/div[1]/div/div[3]/div/a'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
