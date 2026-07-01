#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备基础测试数据
zenData('user')->gen(5);
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
r(strpos($executionTest->buildTreeTestDirect($singleTask)[0]['content']['html'], 'tree-link') !== false && strpos($executionTest->buildTreeTestDirect($singleTask)[0]['content']['html'], '测试任务') !== false) && p() && e('1');

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
        'name' => '测试分支',
        'common' => '分支'
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
r($executionTest->buildTreeTestDirect($nestedTree)[0]['items'][0]['className']) && p() && e('py-2 cursor-pointer task');

// 步骤10：权限控制验证无权限用户访问
su('user2');
r($executionTest->buildTreeTestDirect($singleTask)) && p('0:url') && e('~~');