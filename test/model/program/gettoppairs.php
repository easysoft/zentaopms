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

    public function getTopPairs($count = '')
    {
        if($count == 'count') return count($this->program->getTopPairs());
        return $this->program->getTopPairs();
    }
}

$t = new Tester('admin');

/**

title=测试 programModel::getTopPairs();
cid=1
pid=1

*/

r($t->getTopPairs('count')) && p()    && e('10'); // 查看父项目集的个数
r($t->getTopPairs())        && p('1') && e('项目集1'); // 查看id=1的父项目集

