<?php
class productPage extends Page
{
    public function __construct()
    {
        parent::__construct();

        $doms = array(
            'settings'       => "//*[@id='navbar']//a[@data-id='settings']/span",
            'reviewer'       => "//*[@name='reviewer[]']",
            'branchdropmenu' => "//*[@id='pick-branch-dropmenu']/span",
        );
        $this->doms = array_merge($this->doms, $doms);
    }
}
