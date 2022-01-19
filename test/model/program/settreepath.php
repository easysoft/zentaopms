#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::setTreePath();
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

    public function setTreePath($programID)
    {
        $programPath = $this->program->setTreePath($programID);
        if($programPath)
        {
            return $this->program->getById($programID);
        }
        else
        {
            return 0;
        }
    }
}

$t = new Tester('admin');

/* SetTreePath($programID). */
r($t->setTreePath(12)) && p('path') && e('12,'); // 查找id=11的项目集的path
r($t->setTreePath(1000)) && p('path') && e('0'); // 查找不存在的id=1000的项目集的path
