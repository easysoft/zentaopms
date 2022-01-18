#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getParentPairs();
cid=1
pid=1

*/

class Tester
{
    public function __construct($user)
    {   
        global $tester;

        su(%$user);
        $this->program = $tester->loadModel('program');
    }   

    public function getParentPairs()
    {
        return $this->program->getParentPairs();
    }

    public function getCount()
    {
        return count($this->program->getParentPairs());
    }
}

$t = new Tester('admin');

/* Count(). */
r($t->getCount())       && p()    && e('11'); //

/* GetParentPairs(). */
r($t->getParentPairs()) && p('1') && e(''); // 获取父项目集的id/name关联数组
