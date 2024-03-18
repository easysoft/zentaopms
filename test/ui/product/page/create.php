<?php
class createPage extends Page
{
    public function __construct()
    {
        parent::__construct();

        $doms = array(
        );
        $this->doms = array_merge($this->doms, $doms);
    }

    public function submit()
    {
        global $lang;
        $this->btn($lang->product->saveBtn)->click();
        sleep(1);

        return $this;
    }
}
