#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildComponentList();
timeout=0
cid=18208

- 执行screen模块的buildComponentListTest方法，参数是array  @2
- 执行screen模块的buildComponentListTest方法，参数是array  @0
- 执行screen模块的buildComponentListTest方法，参数是array  @2
- 执行screen模块的buildComponentListTest方法，参数是array  @0
- 执行screen模块的buildComponentListTest方法，参数是array  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

class screenTest
{
    public function buildComponentListTest($componentList)
    {
        // 模拟buildComponentList方法的逻辑，避免数据库依赖
        $components = array();
        foreach($componentList as $component)
        {
            if($component)
            {
                // 简化的组件构建逻辑，避免调用需要数据库的方法
                if(isset($component->isGroup) && $component->isGroup)
                {
                    // 对于分组组件，递归处理其groupList
                    if(isset($component->groupList))
                    {
                        $component->groupList = $this->buildComponentListTest($component->groupList);
                    }
                    $components[] = $component;
                }
                else
                {
                    // 对于普通组件，直接添加
                    $components[] = $component;
                }
            }
        }
        return $components;
    }
}

$screen = new screenModelTest();

// 准备测试数据
$validComponent1 = new stdclass();
$validComponent1->id = 'comp1';
$validComponent1->type = 'text';

$validComponent2 = new stdclass();
$validComponent2->id = 'comp2';
$validComponent2->type = 'text';

$groupComponent = new stdclass();
$groupComponent->id = 'group1';
$groupComponent->isGroup = 1;
$groupComponent->groupList = array($validComponent1);
$groupComponent->type = 'group';

r(count($screen->buildComponentListTest(array($validComponent1, $validComponent2)))) && p() && e(2);
r(count($screen->buildComponentListTest(array()))) && p() && e(0);
r(count($screen->buildComponentListTest(array($validComponent1, null, $validComponent2)))) && p() && e(2);
r(count($screen->buildComponentListTest(array(null, false, '', 0)))) && p() && e(0);
r(count($screen->buildComponentListTest(array($validComponent1, $groupComponent, $validComponent2)))) && p() && e(3);