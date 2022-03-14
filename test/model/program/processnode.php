#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::processNode();
cid=1
pid=1



*/

/*class Tester
{
    public function __construct($user)
    {
        global $tester;

        su($user);
        $this->program = $tester->loadModel('program');
    }
}*/

$t = new Program('admin');

$program = $tester->loadModel('program');
r($program->processNode(1, 0, 1, 1)) && p() && e(''); //