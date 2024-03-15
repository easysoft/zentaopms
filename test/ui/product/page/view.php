<?php
class viewPage extends Page
{
    public function __construct()
    {
        parent::__construct();

        $doms = array(
            'productName' => "//*[@id='mainContent']/div[1]/div[1]/div[1]/div[2]/div[1]/div",
            'type' => "//*[@id='mainContent']/div[1]/div[1]/div[1]/div[2]/div[1]/span[2]",
            'acl'  => "//*[@id='mainContent']/div[1]/div[1]/div[1]/div[2]/div[1]/span[3]",
        );
        $this->doms = array_merge($this->doms, $doms);
    }
}
