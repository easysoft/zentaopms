#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备基础测试数据
zenData('user')->gen(5);
zenData('config')->gen(10);

// 准备权限组数据
$group = zenData('group');
$group->id->range('1-5');
$group->name->range('管理员,开发,测试,产品,项目经理');
$group->role->range('admin,dev,qa,po,pm');
$group->gen(5);

// 准备用户组关系数据
$userGroup = zenData('usergroup');
$userGroup->account->range('admin,user1,user2,user3,user4');
$userGroup->group->range('1,2,3,4,5');
$userGroup->gen(5);

// 准备权限数据
$groupPriv = zenData('grouppriv');
$groupPriv->group->range('1{20},2{15},3{10}');
$groupPriv->module->range('execution{15},task{10},story{10}');
$groupPriv->method->range('treeTask{10},treeStory{5},browse{5},view{5}');
$groupPriv->gen(35);

// 准备语言包数据
$lang = zenData('lang');
$lang->lang->range('zh-cn');
$lang->module->range('task,story,requirement,epic,branch');
$lang->section->range('common');
$lang->key->range('common');
$lang->value->range('任务,需求,需求,史诗,分支');
$lang->gen(5);

su('admin');

/**

title=测试 executionModel::buildTree();
timeout=0
cid=16275

- 空树数组输入测试返回空数组 >> 期望返回空数组
- 单个任务节点构建验证className属性 >> 期望包含正确的CSS类名
- 任务节点内容HTML结构验证 >> 期望包含任务标签和标题
- product类型节点构建验证 >> 期望正确渲染产品节点
- story类型节点构建验证 >> 期望正确渲染需求节点
- requirement类型节点构建验证 >> 期望正确渲染需求节点
- epic类型节点构建验证 >> 期望正确渲染史诗节点
- branch类型节点构建验证 >> 期望正确渲染分支节点
- 包含子节点的嵌套树结构验证 >> 期望正确处理递归子节点
- 权限控制验证无权限用户访问 >> 期望普通用户无URL访问权限

*/

$executionTest = new executionModelTest();

// 步骤1：空树数组输入测试
r($executionTest->buildTreeTestDirect(array())) && p() && e('0');

// 步骤2：单个任务节点构建验证className属性
$singleTask = array(
    (object)array(
        'id' => 1,
        'type' => 'task',
        'title' => '测试任务',
        'assignedTo' => 'admin',
        'avatar' => 'A',
        'avatarAccount' => 'admin',
        'parent' => 0,
        'isParent' => false
    )
);
r($executionTest->buildTreeTestDirect($singleTask)) && p('0:className') && e('py-2 cursor-pointer task');

// 步骤3：任务节点内容HTML结构验证
r($executionTest->buildTreeTestDirect($singleTask)) && p('0:content:html') && e('*tree-link*');

// 步骤4：product类型节点构建验证
$productNode = array(
    (object)array(
        'id' => 1,
        'type' => 'product',
        'name' => '测试产品'
    )
);
r($executionTest->buildTreeTestDirect($productNode)) && p('0:className') && e('py-2 cursor-pointer product');

// 步骤5：story类型节点构建验证
$storyNode = array(
    (object)array(
        'id' => 1,
        'type' => 'story',
        'title' => '测试需求',
        'storyId' => 1,
        'grade' => 1,
        'assignedTo' => '',
        'avatar' => '',
        'avatarAccount' => ''
    )
);
r($executionTest->buildTreeTestDirect($storyNode)) && p('0:className') && e('py-2 cursor-pointer story');

// 步骤6：requirement类型节点构建验证
$requirementNode = array(
    (object)array(
        'id' => 1,
        'type' => 'requirement',
        'title' => '测试需求文档',
        'storyId' => 1,
        'grade' => 1,
        'assignedTo' => 'admin',
        'avatar' => 'A',
        'avatarAccount' => 'admin'
    )
);
r($executionTest->buildTreeTestDirect($requirementNode)) && p('0:className') && e('py-2 cursor-pointer requirement');

// 步骤7：epic类型节点构建验证
$epicNode = array(
    (object)array(
        'id' => 1,
        'type' => 'epic',
        'title' => '测试史诗',
        'storyId' => 1,
        'grade' => 1,
        'assignedTo' => 'admin',
        'avatar' => 'A',
        'avatarAccount' => 'admin'
    )
);
r($executionTest->buildTreeTestDirect($epicNode)) && p('0:className') && e('py-2 cursor-pointer epic');

// 步骤8：branch类型节点构建验证
$branchNode = array(
    (object)array(
        'id' => 1,
        'type' => 'branch',
        'name' => '测试分支'
    )
);
r($executionTest->buildTreeTestDirect($branchNode)) && p('0:className') && e('py-2 cursor-pointer branch');

// 步骤9：包含子节点的嵌套树结构验证
$nestedTree = array(
    (object)array(
        'id' => 1,
        'type' => 'task',
        'title' => '父任务',
        'assignedTo' => 'admin',
        'avatar' => 'A',
        'avatarAccount' => 'admin',
        'parent' => 0,
        'isParent' => true,
        'children' => array(
            (object)array(
                'id' => 2,
                'type' => 'task',
                'title' => '子任务',
                'assignedTo' => 'user1',
                'avatar' => 'U',
                'avatarAccount' => 'user1',
                'parent' => 1,
                'isParent' => false
            )
        )
    )
);
r($executionTest->buildTreeTestDirect($nestedTree)) && p('0:items:0:className') && e('py-2 cursor-pointer task');

// 步骤10：权限控制验证无权限用户访问
su('user2');
r($executionTest->buildTreeTestDirect($singleTask)) && p('0:url') && e('');