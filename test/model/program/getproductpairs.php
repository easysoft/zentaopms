#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getProductPairs();
cid=1
pid=1

根据项目或项目集ID获取关联产品详情 >> 已关闭的正常产品23
获取不存在的项目或项目集 >> Not Found
根据项目或项目集指派情况获取关联产品详情 >> 正常产品1
根据项目或项目集状态获取关联产品详情 >> 多平台产品100
查看ID=1，有指派，所有状态的关联产品数量 >> 10
查看ID=1，有指派，未关闭的关联产品数量 >> 6

*/
/*
class Tester
{
    public function __construct($user)
    {
        global $tester;

        su($user);
        $this->program = $tester->loadModel('program');
    }

    public function getProductPairsByID($programID = 0)
    {
        $program   = $this->program->getByID($programID);
        if(empty($program)) return array('message' => 'Not Found');
        return $this->program->getProductPairs($programID, 'assign', 'all');
    }

    public function getProductPairsByMod($mode = 'assign')
    {
        return $this->program->getProductPairs(1, $mode, 'noclosed');
    }

    public function getProductPairsByStatus($status = 'all')
    {
        return $this->program->getProductPairs(1, 'assign', $status);
    }

    public function getCount($programID = 0, $mode = 'assign', $status = 'all')
    {
        return count($this->program->getProductPairs($programID, $mode, $status));
    }
}
*/
$getItemsets = new Program('admin');

/* GetProductPairs($programID). */
r($getItemsets->getProductPairsByID('1'))       && p('23')      && e('已关闭的正常产品23'); //根据项目或项目集ID获取关联产品详情
r($getItemsets->getProductPairsByID('1000'))    && p('message') && e('Not Found'); //获取不存在的项目或项目集

/* GetProductPairs('all', $mode, 'noclosed'). */
r($getItemsets->getProductPairsByMod('all'))     && p('1')       && e('正常产品1'); // 根据项目或项目集指派情况获取关联产品详情

/* GetProductPairs('all', 'assign', $status). */
r($getItemsets->getProductPairsByStatus('all')) && p('100')     && e('多平台产品100'); //根据项目或项目集状态获取关联产品详情

/* Count().*/
r($getItemsets->getCount2('1', 'assign', 'all'))      && p() && e('10'); //查看ID=1，有指派，所有状态的关联产品数量
r($getItemsets->getCount2('1', 'assign', 'noclosed')) && p() && e('6'); //查看ID=1，有指派，未关闭的关联产品数量