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
        global $uiTester;

        $uiTester->app->loadLang('product');
        $this->btn($this->lang->product->create)->click();
        sleep(1);

        return $this;
    }
}
