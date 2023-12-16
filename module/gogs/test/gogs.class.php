<?php
class gogsTest
{
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $this->gogs   = $this->tester->loadModel('gogs');
    }
}
