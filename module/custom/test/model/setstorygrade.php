#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('storygrade');
su('admin');

/**

title=测试 customModel->setStoryGrade();
timeout=0
cid=1

- 查看插入的等级数量 @5
- 查看插入的等级1详情
 - 第0条的grade属性 @1
 - 第0条的name属性 @一级
 - 第0条的status属性 @enable
- 查看插入的等级5详情
 - 第4条的grade属性 @5
 - 第4条的name属性 @五级
 - 第4条的status属性 @enable

*/

$data['grade'] = array(
    0 => 1,
    1 => 2,
    2 => 3,
    3 => 4,
    4 => 5
);

$data['gradeName'] = array(
    0 => '一级',
    1 => '二级',
    2 => '三级',
    3 => '四级',
    4 => '五级'
);

global $tester;
$tester->loadModel('custom')->setStoryGrade('story', $data);
$gradeSetting = $tester->loadModel('story')->getGradeSetting();

r(count($gradeSetting)) && p()                      && e('5');              // 查看插入的等级数量
r($gradeSetting)        && p('0:grade,name,status') && e('1,一级,enable');  // 查看插入的等级1详情
r($gradeSetting)        && p('4:grade,name,status') && e('5,五级,enable');  // 查看插入的等级5详情