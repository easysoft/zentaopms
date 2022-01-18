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

    function update($programID, $data)
    {
        global $app;

        $_POST = $data;
        $result = $this->program->update(10);
        if(dao::isError()) return array('message' => dao::getError());

        $app->dbh->query("UPDATE " . TABLE_PROGRAM . " SET name = '" . $result[0]['old']. "' where id = '" . $programID . "'");
        return $result;
    }

    function updateProgram($programID, $status = 0)
    {
        $data = array(
            'parent' => '0',
            'name' => '测试更新项目集十',
            'begin' => '2020-10-10',
            'end' => '2020-10-11',
            'acl' => 'private',
            'budget' => '100',
            'budgetUnit' => 'CNY'
        );

        switch($status)
        {
        case '1': // 项目集名称已经存在时
            $data['name'] = '项目集1';
            break;
        case '2': // 当计划开始为空时更新项目集信息
            $data['begin'] = '';
            break;
        case '3': // 当计划完成为空时更新项目集信息
            $data['end'] = '';
            break;
        case '4': // 当计划完成小于计划开始时
            $data['end'] = '2020-01-01';
            break;
        case '5': // 项目集开始时间小于父项目集时
            $data['parent'] = '9';
            $data['begin']  = '2019-01-01';
            break;
        default: // 更新id为10的项目集信息
        }
        return $this->update($programID, $data);
    }
}

$t = new Tester('admin');

/**

title=测试 programModel::update($programID);
cid=1
pid=1

 */

r($t->updateProgram(10))    && p('0:new')                     && e('测试更新项目集十');// 更新id为10的项目集信息
r($t->updateProgram(10, 2)) && p('message[begin]:0')          && e('『计划开始』不能为空。');// 当计划开始为空时更新项目集信息
r($t->updateProgram(10, 3)) && p('message[end]:0')            && e('『计划完成』不能为空。');// 当计划完成为空时更新项目集信息
r($t->updateProgram(10, 4)) && p('message[end]:0')            && e('『计划完成』应当大于『2020-10-10』。');// 当计划完成小于计划开始时
r($t->updateProgram(10, 1)) && p('message[name]:0')           && e('『项目集名称』已经有『项目集1』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。');// 项目集名称已经存在时
r($t->updateProgram(10, 5)) && p('message:begin;message:end') && e('父项目集的开始日期：2019-09-09，开始日期不能小于父项目集的开始日期;父项目集的完成日期：2019-09-09，完成日期不能大于父项目集的完成日期');// 项目集开始时间小于父项目集时
