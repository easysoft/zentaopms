#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试productModel->getLatestProject();
cid=1
pid=1

*/
class Tester
{
    public function __construct($user)
    {
        global $tester;
        su($user);
        $this->product = $tester->loadModel('product');
    }

    public function testGetLatestProject($productID)
    {
        $project = $this->product->getLatestProject($productID);
        if($project == false) return '没有数据';
        return $project;
    }
}

$z = new Tester('admin');

r($z->testGetLatestProject(25))  && p('id') && e('315'); // 测试产品25关联的最后一个未关闭的项目,按begin字段排序
r($z->testGetLatestProject(38))  && p('id') && e('688'); // 测试产品38关联的最后一个未关闭的项目,按begin字段排序
r($z->testGetLatestProject(101)) && p()     && e('没有数据'); // 传入不存在的产品
