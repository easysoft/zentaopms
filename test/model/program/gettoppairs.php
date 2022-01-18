#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getTopPairs();
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

    public function getTopPairs($count = '')
    {
        if($count == 'count') return count($this->program->getTopPairs());
        return $this->program->getTopPairs();
    }
}

$t = new Tester('admin');

/* getTopPairs(). */
r($t->getTopPairs())        && p('1') && e('项目集1'); // 查看id=1的父项目集

/* Count(). */
r($t->getTopPairs('count')) && p()    && e('10'); // 查看父项目集的个数

