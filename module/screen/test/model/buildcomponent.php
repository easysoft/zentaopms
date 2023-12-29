#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

zdTable('product')->gen(2);
/**
title=测试 screenModel->buildComponent();
cid=1
pid=1

有图表id的元素判断是否正常生成了刻度和数据。 >> 1
判断是否正常生成了Select组件                 >> 1
非列表的组件判断是否给予了默认的属性。       >> 1
列表的组件判断是否给予了默认的属性。         >> 1
*/

$screen = new screenTest();

$components = $screen->getAllComponent();

$component1 = null;
$component2 = null;
$component3 = null;
$component4 = null;
foreach($components as $component)
{
    if(isset($component->sourceID) && $component->sourceID)
    {
        $component1 = $component;
    }
    elseif(isset($component->key) && $component->key === 'Select')
    {
        $component2 = $component;
    }
    elseif(empty($component->isGroup))
    {
        $component3 = $component;
    }
    else
    {
        $component4 = $component;
    }
}

if($component1) $screen->buildComponentTest($component1);
r(isset($component1->option->dataset[0][0]) && $component1->option->dataset[0][0] == '正常产品1') && p('') && e('0');  //有图表id的元素判断是否正常生成了刻度和数据。

if($component2) $screen->buildComponentTest($component2);
r(isset($component2) && $component2->option->dataset[0]->label == '请选择') && p('') && e('1');  //判断是否正常生成了Select组件。

if($component3) $screen->buildComponentTest($component3);
r($component3->styles && $component->status && $component->request) && p('') && e('1');  //非列表的组件判断是否给予了默认的属性。

if($component4) $screen->buildComponentTest($component4);
r($component3->styles && $component->status && $component->request) && p('') && e('1'); //列表的组件判断是否给予了默认的属性。
