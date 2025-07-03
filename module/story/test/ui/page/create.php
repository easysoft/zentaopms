<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'srCommen'    => "//*[@id='zin_story_create_form']/div[3]/div[1]/label/span",
            'titleInLite' => "//*[@id='title']",
            'saveInLite'  => "//*[@id='zin_story_create_form']/div[8]/div/button[1]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
