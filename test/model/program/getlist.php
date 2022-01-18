#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

su('admin');
class Tester
{
    public function __construct($user)
    {
        global $tester;

        su($user);
        $this->program = $tester->loadModel('program');
    }

    public function getListByStatus($status)
    {
        $this->program->cookie->showClosed = ture;
        $programs = $this->program->getList($status);
        if(!$programs) return 0;
        foreach($programs as $program)
        {
            if($program->status != $status and $status != 'all') return 0;
        }
        return count($programs);
    }

    public function getListByOrder($orderBy)
    {
        $programs = $this->program->getList('all', $orderBy);
        return checkOrder($programs, $orderBy);
    }
}

$t = new Tester('admin');

/**

title=测试 programModel::getList();
cid=1
pid=1


*/

/* GetList($status). */
r($t->getListByStatus('all'))       && p() && e('100'); // 查看所有项目和项目集的个数
r($t->getListByStatus('wait'))      && p() && e('34'); // 查看所有'wait'的项目和项目集的个数
r($t->getListByStatus('doing'))     && p() && e('44'); // 查看所有'doing'的项目和项目集的个数
r($t->getListByStatus('suspended')) && p() && e('11'); // 查看所有'suspended'的项目和项目集的个数
r($t->getListByStatus('closed'))    && p() && e('11'); // 查看所有'closed'的项目和项目集的个数

/* GetList('all', $orderBy). */
r($t->getListByOrder('name_desc')) && p() && e(1); // 按照项目和项目集名称倒序获取项目列表
r($t->getListByOrder('id_asc'))    && p() && e(1); // 按照ID正序获取项目和项目集列表
