#!/usr/bin/env php
<?php

/**

title=测试pivotModel->getTableHeader();
timeout=0
cid=17404

- 获取合并列头
 - 第0条的name属性 @id
 - 第0条的isGroup属性 @1
 - 第0条的label属性 @编号
- 获取合并列头
 - 第1条的name属性 @id
 - 第1条的isGroup属性 @~~
 - 第1条的label属性 @编号的计数(总计百分比)
- 获取子列头
 - 第0条的name属性 @0
 - 第0条的isGroup属性 @~~
 - 第0条的label属性 @空
- 获取子列头
 - 第1条的name属性 @1
 - 第1条的isGroup属性 @~~
 - 第1条的label属性 @1
- 获取子列头
 - 第2条的name属性 @2
 - 第2条的isGroup属性 @~~
 - 第2条的label属性 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('program')->gen(0);
zenData('product')->gen(20);

global $tester;

$columnRows = $tester->dao->select('*')->from(TABLE_PRODUCT)->fetchAll('', false);

$dirll = new stdclass();
$dirll->pivot     = 1029;
$dirll->version   = 1;
$dirll->field     = 'id';
$dirll->object    = 'product';
$dirll->whereSql  = '';
$dirll->condition = array
(
    '0' => array
    (
        'drillObject' => 'zt_product',
        'drillAlias'  => 't1',
        'drillField'  => 'id',
        'queryField'  => 'id'
    ),
    '1' => array
    (
        'drillObject' => 'zt_product',
        'drillAlias'  => 't1',
        'drillField'  => 'program',
        'queryField'  => 'program'
    )
);
$dirll->status  = 'published';
$dirll->account = '';
$dirll->type    = 'auto';

$settings = Array
(
    'field' => 'id', 'slice' => 'program', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'total', 'monopolize' => 1, 'showOrigin' => 0,
    'drill' => $dirll
);

$productFields = array
(
    'id' => array( 'object' => 'product', 'field' => 'id', 'type' => 'number'),
    'program' => array( 'object' => 'product', 'field' => 'program', 'type' => 'string'),
    'name' => array( 'object' => 'product', 'field' => 'name', 'type' => 'string'),
    'code' => array( 'object' => 'product', 'field' => 'code', 'type' => 'string'),
    'shadow' => array( 'object' => 'product', 'field' => 'shadow', 'type' => 'number'),
    'bind' => array( 'object' => 'product', 'field' => 'bind', 'type' => 'string'),
    'line' => array( 'object' => 'product', 'field' => 'line', 'type' => 'string'),
    'type' => array( 'object' => 'product', 'field' => 'type', 'type' => 'option'),
    'status' => array( 'object' => 'product', 'field' => 'status', 'type' => 'option'),
    'subStatus' => array( 'object' => 'product', 'field' => 'subStatus', 'type' => 'string'),
    'desc' => array( 'object' => 'product', 'field' => 'desc', 'type' => 'string'),
    'PO' => array( 'object' => 'product', 'field' => 'PO', 'type' => 'user'),
    'QD' => array( 'object' => 'product', 'field' => 'QD', 'type' => 'user'),
    'RD' => array( 'object' => 'product', 'field' => 'RD', 'type' => 'user'),
    'feedback' => array( 'object' => 'product', 'field' => 'feedback', 'type' => 'string'),
    'ticket' => array( 'object' => 'product', 'field' => 'ticket', 'type' => 'string'),
    'workflowGroup' => array( 'object' => 'product', 'field' => 'workflowGroup', 'type' => 'number'),
    'acl' => array( 'object' => 'product', 'field' => 'acl', 'type' => 'string'),
    'groups' => array( 'object' => 'product', 'field' => 'groups', 'type' => 'string'),
    'whitelist' => array( 'object' => 'product', 'field' => 'whitelist', 'type' => 'string'),
    'reviewer' => array( 'object' => 'product', 'field' => 'reviewer', 'type' => 'string'),
    'PMT' => array( 'object' => 'product', 'field' => 'PMT', 'type' => 'string'),
    'draftEpics' => array( 'object' => 'product', 'field' => 'draftEpics', 'type' => 'number'),
    'activeEpics' => array( 'object' => 'product', 'field' => 'activeEpics', 'type' => 'number'),
    'changingEpics' => array( 'object' => 'product', 'field' => 'changingEpics', 'type' => 'number'),
    'reviewingEpics' => array( 'object' => 'product', 'field' => 'reviewingEpics', 'type' => 'number'),
    'finishedEpics' => array( 'object' => 'product', 'field' => 'finishedEpics', 'type' => 'number'),
    'closedEpics' => array( 'object' => 'product', 'field' => 'closedEpics', 'type' => 'number'),
    'totalEpics' => array( 'object' => 'product', 'field' => 'totalEpics', 'type' => 'number'),
    'draftRequirements' => array( 'object' => 'product', 'field' => 'draftRequirements', 'type' => 'number'),
    'activeRequirements' => array( 'object' => 'product', 'field' => 'activeRequirements', 'type' => 'number'),
    'changingRequirements' => array( 'object' => 'product', 'field' => 'changingRequirements', 'type' => 'number'),
    'reviewingRequirements' => array( 'object' => 'product', 'field' => 'reviewingRequirements', 'type' => 'number'),
    'finishedRequirements' => array( 'object' => 'product', 'field' => 'finishedRequirements', 'type' => 'number'),
    'closedRequirements' => array( 'object' => 'product', 'field' => 'closedRequirements', 'type' => 'number'),
    'totalRequirements' => array( 'object' => 'product', 'field' => 'totalRequirements', 'type' => 'number'),
    'draftStories' => array( 'object' => 'product', 'field' => 'draftStories', 'type' => 'number'),
    'activeStories' => array( 'object' => 'product', 'field' => 'activeStories', 'type' => 'number'),
    'changingStories' => array( 'object' => 'product', 'field' => 'changingStories', 'type' => 'number'),
    'reviewingStories' => array( 'object' => 'product', 'field' => 'reviewingStories', 'type' => 'number'),
    'finishedStories' => array( 'object' => 'product', 'field' => 'finishedStories', 'type' => 'number'),
    'closedStories' => array( 'object' => 'product', 'field' => 'closedStories', 'type' => 'number'),
    'totalStories' => array( 'object' => 'product', 'field' => 'totalStories', 'type' => 'number'),
    'unresolvedBugs' => array( 'object' => 'product', 'field' => 'unresolvedBugs', 'type' => 'number'),
    'closedBugs' => array( 'object' => 'product', 'field' => 'closedBugs', 'type' => 'number'),
    'fixedBugs' => array( 'object' => 'product', 'field' => 'fixedBugs', 'type' => 'number'),
    'totalBugs' => array( 'object' => 'product', 'field' => 'totalBugs', 'type' => 'number'),
    'plans' => array( 'object' => 'product', 'field' => 'plans', 'type' => 'number'),
    'releases' => array( 'object' => 'product', 'field' => 'releases', 'type' => 'number'),
    'createdBy' => array( 'object' => 'product', 'field' => 'createdBy', 'type' => 'user'),
    'createdDate' => array( 'object' => 'product', 'field' => 'createdDate', 'type' => 'date'),
    'createdVersion' => array( 'object' => 'product', 'field' => 'createdVersion', 'type' => 'string'),
    'closedDate' => array( 'object' => 'product', 'field' => 'closedDate', 'type' => 'date'),
    'order' => array( 'object' => 'product', 'field' => 'order', 'type' => 'number'),
    'vision' => array( 'object' => 'product', 'field' => 'vision', 'type' => 'string'),
    'deleted' => array( 'object' => 'product', 'field' => 'deleted', 'type' => 'string')
);

$col = new stdclass();
$col->name    = 'id';
$col->field   = 'id';
$col->isGroup = '1';
$col->label   = '编号';

$cols = array(array($col));
$sql  = 'select * from zt_product';

$cols = $tester->loadModel('pivot')->getTableHeader($columnRows, $settings, $productFields, $cols, $sql);

r($cols[0]) && p('0:name,isGroup,label') && e('id,1,编号');                    // 获取合并列头
r($cols[0]) && p('1:name,isGroup,label') && e('id,~~,编号的计数(总计百分比)'); // 获取合并列头
r($cols[1]) && p('0:name,isGroup,label') && e('0,~~,空');                      // 获取子列头
r($cols[1]) && p('1:name,isGroup,label') && e('1,~~,1');                       // 获取子列头
r($cols[1]) && p('2:name,isGroup,label') && e('2,~~,2');                       // 获取子列头
