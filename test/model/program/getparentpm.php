#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

class Tester
{
    public function __construct($user)
    {   
        global $tester;

        su('admin');
        $this->program = $tester->loadModel('program');
    }

    public function getParentPM($programIdList)
    {
        return $this->program->getParentPM($programIdList);
    }
}

$t = new Tester('admin');

/**

title=测试 programModel::getParentPM($programIdList);
cid=1
pid=1

*/

r($t->getParentPM('1')) && p() && e('0'); // 
