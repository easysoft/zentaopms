#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 projectModel::checkHasChildren();
cid=1
pid=1


*/
class Tester
{
    public function __construct($user)
    {
        global $tester;

        su($user);
        $this->project = $tester->loadModel('project');
    }

    public function checkHasChildren($projectID)
    {
        return $this->project->checkHasChildren($projectID);
    }
}

$t = new Tester('admin');

r($t->checkHasChildren(1))   && p() && e('1'); //获取id为1的项目是否有子项目
r($t->checkHasChildren(101)) && p() && e('0'); //获取id为101的项目是否有子项目
