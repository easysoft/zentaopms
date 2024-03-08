<?php
class productPage extends Page
{
    public function __construct($driver)
    {
        parent::__construct($driver);

        $doms = array(
            'settings'       => "//*[@id='navbar']//a[@data-id='settings']/span",
            'reviewer'       => "//*[@name='reviewer[]']",
            'branchdropmenu' => "//*[@id='pick-branch-dropmenu']/span",
        );
        $this->doms = array_merge($this->doms, $doms);
    }
}
