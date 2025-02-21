<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'sumCount' => '//*[@id="featureBar"]/menu/li/a/span[2]',
            'type'     => '//*[@id="table-testsuite-browse"]/div[2]/div[1]/div/div[2]/div/div[1]/span',
            'name'     => '//*[@id="table-testsuite-browse"]/div[2]/div[1]/div/div[2]/div/div[2]/a',
            /*列表底部统计数*/
            'footerSumCount'     => '//*[@id="table-testsuite-browse"]/div[3]/div[1]/strong[1]',
            'footerPublicCount'  => '//*[@id="table-testsuite-browse"]/div[3]/div[1]/strong[2]',
            'footerPrivateCount' => '//*[@id="table-testsuite-browse"]/div[3]/div[1]/strong[3]'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
