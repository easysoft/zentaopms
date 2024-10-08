<?php
class groupPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'groupName'      => "//*[@class='modal-content']/div[3]/div/div/form/div/input",
            'groupDesc'      => "//*[@class='modal-content']/div[3]/div/div/form/div[2]/textarea",
            'groupNameList'  => "//*[@id='table-project-group']/div[2]/div[1]/div/div[2]/div",
            'groupDescList'  => "//*[@id='table-project-group']/div[2]/div[2]/div/div/div",
            'createGroupBtn' => "//*[@id='mainMenu']/div[2]/a/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
