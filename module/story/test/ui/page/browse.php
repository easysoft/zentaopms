<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'browseStoryName' => "//*[@id='stories']/div[2]/div[1]/div/div[2]/div/a",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
