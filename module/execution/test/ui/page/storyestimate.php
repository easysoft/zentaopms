<?php
class storyestimatePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'firstEstimateBtn' => "//*[@id='table-execution-story']/div[2]/div[3]/div/div[1]/div/nav/a[2]",
            'reestimate'       => "//*[@id='storyEstimateTable']/../../div/button",
            'estimateA'        => "//*[@id='storyEstimateTable']/tbody/tr[2]/td[2]/input",
            'estimateB'        => "//*[@id='storyEstimateTable']/tbody/tr[3]/td[2]/input",
            'estimateC'        => "//*[@id='storyEstimateTable']/tbody/tr[4]/td[2]/input",
            'average'          => "//*[@id='storyEstimateTable']/tbody/tr[5]/td[2]/input",
            'newEstimateA'     => "//*[@id='storyEstimateTable']/tbody/tr[2]/td[3]/input",
            'newEstimateB'     => "//*[@id='storyEstimateTable']/tbody/tr[3]/td[3]/input",
            'newEstimateC'     => "//*[@id='storyEstimateTable']/tbody/tr[4]/td[3]/input",
            'newAverage'       => "//*[@id='storyEstimateTable']/tbody/tr[5]/td[3]/input",
            'submitBtn'        => "//*[@id='storyEstimateTable']/../div/button",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
