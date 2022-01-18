#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::create();
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
    
    function create($data)
    {
        global $app;
        
        $_POST = '';
        $_POST = $data;

        $programID = $this->program->create();

        if(dao::isError()) return array('message' => dao::getError());

        $program = $this->program->getById($programID);

        $app->dbh->query("DELETE FROM ". TABLE_PROGRAM ." where name = '" . $data['name']. "'");
        return $program;
    }

    function createData($status)
    {
        $data = array(
            'parent'     => 0,
            'name'       => '测试新增项目集一',
            'budget'     => '',
            'budgetUnit' => 'CNY',
            'begin'      => '2022-01-12',
            'end'        => '2022-02-12',
            'desc'       => '测试项目集描述',
            'acl'        => 'private'
        );

        switch($status)
        {
        case '1': // 创建新项目集
            break;
        case '2': // 项目集名称为空时
            $data['name']   = '';
            break;
        case '3': // 项目集的开始时间为空
            $data['begin']  = '';
            break;
        case '4': // 项目集的完成时间为空
            $data['end']    = '';
            break;
        case '5': // 项目集的计划完成时间大于计划开始时间
            $data['end']    = '2022-01-10';
            break;
        case '6': // 项目集的完成日期大于父项目集的完成日期
            $data['parent'] = '1';
            $data['begin']  = '2018-01-01';
            $data['end']    = '2022-02-10';
            break;
        default:
        }
        return $this->create($data);
    }
}

$t = new Tester('admin');

r($t->createData(1)) && p('name')                      && e('测试新增项目集一'); // 创建新项目集
r($t->createData(2)) && p('message[name]:0')           && e('『项目集名称』不能为空。'); // 项目集名称为空时
r($t->createData(3)) && p('message[begin]:0')          && e('『计划开始』不能为空。'); // 项目集的开始时间为空
r($t->createData(4)) && p('message[end]:0')            && e('『计划完成』不能为空。'); // 项目集的完成时间为空
r($t->createData(5)) && p('message[end]:0')            && e('『计划完成』应当大于『2022-01-12』。'); // 项目集的计划完成时间大于计划开始时间
r($t->createData(6)) && p('message:begin;message:end') && e('父项目集的开始日期：2019-01-01，开始日期不能小于父项目集的开始日期;父项目集的完成日期：2019-01-01，完成日期不能大于父项目集的完成日期'); // 项目集的完成日期大于父项目集的完成日期
