#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->getRelatedDataByActions();
cid=1
pid=1

获取动态1 2 3 4 5 objectNames的关联信息 >> 项目集5
获取动态1 2 3 4 5 objectNames的关联信息 >> guest
获取动态1 2 3 4 5 relatedProjects的关联信息 >> 5
获取动态1 2 3 4 5 requirements的关联信息 >> 0
获取动态6 7 8 9 10 objectNames的关联信息 >> 这个是测试用例10
获取动态6 7 8 9 10 objectNames的关联信息 >> guest
获取动态6 7 8 9 10 relatedProjects的关联信息 >> 0
获取动态6 7 8 9 10 requirements的关联信息 >> 0
获取动态11 12 13 14 15 objectNames的关联信息 >> 测试单15的待办
获取动态11 12 13 14 15 objectNames的关联信息 >> guest
获取动态11 12 13 14 15 relatedProjects的关联信息 >> 0
获取动态11 12 13 14 15 requirements的关联信息 >> 0
获取动态16 17 18 19 20 objectNames的关联信息 >> 这是测试套件名称19
获取动态16 17 18 19 20 objectNames的关联信息 >> guest
获取动态16 17 18 19 20 relatedProjects的关联信息 >> 0
获取动态16 17 18 19 20 requirements的关联信息 >> 0
获取动态21 22 23 24 25 objectNames的关联信息 >> 用户需求25
获取动态21 22 23 24 25 objectNames的关联信息 >> guest
获取动态21 22 23 24 25 requirements的关联信息 >> 25

*/

$actions = array('1,2,3,4,5', '6,7,8,9,10', '11,12,13,14,15', '16,17,18,19,20', '21,22,23,24,25');

$action = new actionTest();

r($action->getRelatedDataByActionsTest($actions[0], 'objectNames'))     && p('project:5')  && e('项目集5');             // 获取动态1 2 3 4 5 objectNames的关联信息
r($action->getRelatedDataByActionsTest($actions[0], 'objectNames'))     && p('user:0')     && e('guest');               // 获取动态1 2 3 4 5 objectNames的关联信息
r($action->getRelatedDataByActionsTest($actions[0], 'relatedProjects')) && p('project:5')  && e('5');                   // 获取动态1 2 3 4 5 relatedProjects的关联信息
r($action->getRelatedDataByActionsTest($actions[0], 'requirements'))    && p()             && e('0');                   // 获取动态1 2 3 4 5 requirements的关联信息
r($action->getRelatedDataByActionsTest($actions[1], 'objectNames'))     && p('case:10')    && e('这个是测试用例10');    // 获取动态6 7 8 9 10 objectNames的关联信息
r($action->getRelatedDataByActionsTest($actions[1], 'objectNames'))     && p('user:0')     && e('guest');               // 获取动态6 7 8 9 10 objectNames的关联信息
r($action->getRelatedDataByActionsTest($actions[1], 'relatedProjects')) && p('case:10')    && e('0');                   // 获取动态6 7 8 9 10 relatedProjects的关联信息
r($action->getRelatedDataByActionsTest($actions[1], 'requirements'))    && p()             && e('0');                   // 获取动态6 7 8 9 10 requirements的关联信息
r($action->getRelatedDataByActionsTest($actions[2], 'objectNames'))     && p('todo:15')    && e('测试单15的待办');      // 获取动态11 12 13 14 15 objectNames的关联信息
r($action->getRelatedDataByActionsTest($actions[2], 'objectNames'))     && p('user:0')     && e('guest');               // 获取动态11 12 13 14 15 objectNames的关联信息
r($action->getRelatedDataByActionsTest($actions[2], 'relatedProjects')) && p('doc:13')     && e('0');                   // 获取动态11 12 13 14 15 relatedProjects的关联信息
r($action->getRelatedDataByActionsTest($actions[2], 'requirements'))    && p()             && e('0');                   // 获取动态11 12 13 14 15 requirements的关联信息
r($action->getRelatedDataByActionsTest($actions[3], 'objectNames'))     && p('caselib:19') && e('这是测试套件名称19');  // 获取动态16 17 18 19 20 objectNames的关联信息
r($action->getRelatedDataByActionsTest($actions[3], 'objectNames'))     && p('user:0')     && e('guest');               // 获取动态16 17 18 19 20 objectNames的关联信息
r($action->getRelatedDataByActionsTest($actions[3], 'relatedProjects')) && p('caselib:19') && e('0');                   // 获取动态16 17 18 19 20 relatedProjects的关联信息
r($action->getRelatedDataByActionsTest($actions[3], 'requirements'))    && p()             && e('0');                   // 获取动态16 17 18 19 20 requirements的关联信息
r($action->getRelatedDataByActionsTest($actions[4], 'objectNames'))     && p('story:25')   && e('用户需求25');          // 获取动态21 22 23 24 25 objectNames的关联信息
r($action->getRelatedDataByActionsTest($actions[4], 'objectNames'))     && p('user:0')     && e('guest');               // 获取动态21 22 23 24 25 objectNames的关联信息
r($action->getRelatedDataByActionsTest($actions[4], 'relatedProjects')) && p('story:0')    && e('');                    // 获取动态21 22 23 24 25 relatedProjects的关联信息
r($action->getRelatedDataByActionsTest($actions[4], 'requirements'))    && p('25')         && e('25');                  // 获取动态21 22 23 24 25 requirements的关联信息