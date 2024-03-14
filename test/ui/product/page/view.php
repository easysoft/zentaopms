<?php
class viewPage extends Page
{
    public function __construct()
    {
        parent::__construct();

        $doms = array(
            'settings'       => "//*[@id='navbar']//a[@data-id='settings']/span",
        );
        $this->doms = array_merge($this->doms, $doms);
    }
}
