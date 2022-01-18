#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getParentPM();
cid=1
pid=1

*/

class Tester
{
    public function __construct($user)
    {   
        global $tester;

        su($user);
        $this->program = $tester->loadModel('program');
    }

    public function getParentPM($programIdList)
    {
        return $this->program->getParentPM($programIdList);
    }
}

$t = new Tester('admin');

/* GetParentPM($programIdList). */
r($t->getParentPM('1')) && p() && e('0'); // 
