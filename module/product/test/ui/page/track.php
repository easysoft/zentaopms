<?php
class trackPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'ER'        => '//*[@id="track"]/div[1]/div/div/div[2]/div/div[2]/div[1]/div/div/div/div/div[1]/span/div/div/a',
            'UR'        => '//*[@id="track"]/div[1]/div/div/div[2]/div/div[2]/div[2]/div/div/div/div/div[1]/span/div/div/a',
            'SR'        => '//*[@id="track"]/div[1]/div/div/div[2]/div/div[2]/div[3]/div/div/div/div/div[1]/span/div/div/a',
            'sub_SR'    => '//*[@id="track"]/div[1]/div/div/div[2]/div/div[2]/div[4]/div/div/div/div/div[1]/span/div/div/a',
            'project'   => '//*[@id="track"]/div[1]/div/div/div[2]/div/div[2]/div[5]/div/div/div/div/div[1]/span/div[1]/div/a',
            'execution' => '//*[@id="track"]/div[1]/div/div/div[2]/div/div[2]/div[6]/div/div/div/div/div[1]/span/div[1]/div/a',
            'task'      => '//*[@id="track"]/div[1]/div/div/div[2]/div/div[2]/div[9]/div/div/div/div/div[1]/span/div/div/a',
            'bug'       => '//*[@id="track"]/div[1]/div/div/div[2]/div/div[2]/div[10]/div/div/div/div/div[1]/span/div/div/a',
            'case'      => '//*[@id="track"]/div[1]/div/div/div[2]/div/div[2]/div[11]/div/div/div/div/div[1]/span/div/div/a'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
