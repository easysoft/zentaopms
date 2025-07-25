<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            /*运营界面创建目标页面*/
            'srCommen'    => "//*[@id='zin_story_create_form']/div[3]/div[1]/label/span",
            'titleInLite' => "//*[@id='title']",
            'saveInLite'  => "//*[@id='zin_story_create_form']/div[8]/div/button[1]",
            'reviewerAdmin' => "//*[@class='pick-container']//menu/menu/li/a/div/div[text()='admin']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
