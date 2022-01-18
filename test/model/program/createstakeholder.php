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

    public function createStakeholder($programID)
    {
        $_POST['accounts'] = array('dev1', 'dev2');
        $this->program->createStakeholder($programID);

        return $this->program->getStakeholdersByPrograms($programID);
    }
}

$t = new Tester('admin');

/**

title=测试 programModel::createStakeholder();
cid=1
pid=1

*/
r($t->createStakeholder(1)) && p('0:account;1:account') && e('dev2;dev1'); // 创建id=1的项目集的干系人dev1,dev2并查看。
