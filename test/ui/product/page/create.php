<?php
class createPage extends Page
{
    public function __construct()
    {
        parent::__construct();

        $doms = array(
            'settings'       => "//*[@id='navbar']//a[@data-id='settings']/span",
        );
        $this->doms = array_merge($this->doms, $doms);
    }

    public function submit()
    {
        global $lang;
        $this->btn($lang->product->addBtn)->click();

        return $this;
    }
}
