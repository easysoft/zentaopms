#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getStakeholdersByPrograms();
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

    public function getByPrograms($programIdList = 0) 
    {
        $stakeHolders = $this->program->getStakeholdersByPrograms($programIdList);

        return $stakeHolders;
    }

    public function getCount($programIdList = 0)
    {
        $stakeHolders = $this->program->getStakeholdersByPrograms($programIdList);

        return count($stakeHolders);
    }
}

$t = new Tester('admin');

/* GetStakeholdersByPrograms($programIdList). */
r($t->getByPrograms('2'))   && p() && e('0'); // 获取项目集2的干系人名单
r($t->getByPrograms('2,3')) && p() && e('0'); // 获取项目集2和项目集3的干系人名单

/* Count(). */
r($t->getCount('2'))   && p() && e('0'); // 获取项目集2的干系人个数
r($t->getCount('2,3')) && p() && e('0'); // 获取项目集2和项目集3的干系人个数
