#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getPairs();
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

    public function getPairs()
    {
        $programs = $this->program->getPairs();
        if(!$programs) return 0;
        return $programs;
    }

    public function getCount()
    {
        return count($this->program->getPairs());
    }
}

$t = new Tester('admin');

/* Count().*/
r($t->getCount()) && p()     && e('10'); // 获取项目集个数

/* GetPairs().*/
r($t->getPairs()) && p('1')  && e('项目集1'); // 获取项目集id/name 的关联数组
r($t->getPairs()) && p('9')  && e('项目集9'); // 获取项目集id/name 的关联数组
r($t->getPairs()) && p('11') && e(''); // 获取不存在的项目集id/name 的关联数组
