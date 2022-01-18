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

/**

title=测试 programModel::getParentPairs($model = '', $mode = 'noclosed');
cid=1
pid=1

*/

r($t->getParentPairs()) && p('1') && e(''); // 获取父项目集的id/name关联数组
r($t->getCount())       && p()    && e('11'); //
