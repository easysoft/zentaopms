<?php
class reportPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'charta' => "//*[@value='testTaskPerRunResult']",
            'chartb' => "//*[@value='testTaskPerType']",
            'chartc' => "//*[@value='testTaskPerModule']",
            'chartd' => "//*[@value='testTaskPerRunner']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
