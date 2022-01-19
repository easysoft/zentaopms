#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getStakeholders();
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

    public function getByID($programID = 0)
    {
        $stakeholders = $this->program->getStakeholders($programID);

        return $stakeholders;
    }

    public function getByOrder($orderBy = 'id_desc')
    {
        $stakeholders = $this->program->getStakeholders(2, $orderBy);

        return checkOrder($stakeholders, $orderBy);
    }

    public function getCount($programID = 0)
    {
        $stakeholders = $this->program->getStakeholders($programID);

        return count($stakeholders);
    }
}

$t = new Tester('admin');

/* GetStakeholders($programID). */
r($t->getByID(2)) && p() && e('0'); // 查看项目集2的干系人信息

/* GetStakeholders('2', $orderBy). */
r($t->getByOrder('id_desc')) && p() && e('1'); // 根据干系人id倒序排序
r($t->getByOrder('id_asc'))  && p() && e('1'); // 根据干系人id正序排序

/* Count(). */
r($t->getCount(2)) && p() && e('0'); // 查看项目集2的干系人个数
