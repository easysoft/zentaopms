<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'parentStoryName' => "//*[@id='zin_requirement_view_tabPane']/div/div[3]/div[2]/div/div/a"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
