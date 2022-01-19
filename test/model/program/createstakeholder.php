#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::createStakeholder();
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

    public function createStakeholder($programID)
    {
        $_POST['accounts'] = array('dev1', 'dev2');
        $stakeHolder = $this->program->createStakeholder($programID);

        return $this->program->getStakeholdersByPrograms($programID);
    }
}

$t = new Tester('admin');

/*CreateStakeholder($programID). */
r($t->createStakeholder(1)) && p('0:account;1:account') && e('dev2;dev1'); // 创建id=1的项目集的干系人dev1,dev2并查看。
