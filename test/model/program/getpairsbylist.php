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

    public function getPairsByList($programIDList = '')
    {
        if(empty($this->program->getPairsByList($programIDList)))
        {
            return array('code' => 'fail', 'message' => 'Not Found');
        }
        else
        {
            return $this->program->getPairsByList($programIDList);
        }
    }
}
   
$t = new Tester('admin');

/**

title=测试 programModel::getPairsByList();
cid=1
pid=1

*/

r($t->getPairsByList('1'))          && p('1')       && e('项目集1'); // 通过字符串'1'获取项目集名称
r($t->getPairsByList('1,2,3'))      && p('1,2,3')   && e('项目集1,项目集2,项目集3'); // 通过字符串'1,2,3'获取项目集名称
r($t->getPairsByList(array(1)))     && p('1')       && e('项目集1');//通过数组array(1)获取项目集名称
r($t->getPairsByList(array(1,2,3))) && p('1,2,3')   && e('项目集1,项目集2,项目集3');//通过数组array(1,2,3)获取项目集名称
r($t->getPairsByList())             && p('message') && e('Not Found');// 通过id为空获取项目集名称
r($t->getPairsByList('0'))          && p('message') && e('Not Found');// 通过id=0获取项目集名称
r($t->getPairsByList('11'))         && p('message') && e('Not Found');// 通过id=11获取项目集名称

