#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

function initData()
{
    /* Generate product data. */
    $product = zdTable('product')->config('getlist');
    $product->name->prefix('产品_')->range('1-50');
    $product->code->prefix('p_code_')->range('1-50');
    $product->order->range('1-50');
    $product->status->range('noclosed,involved,review,normal,closed');
    $product->vision->range('rnd,lite,');
    $product->gen(50);

    /* Generate releated program data. */
    $program = zdTable('project');
    $program->id->range('1-10');
    $program->model->range('');
    $program->name->prefix('项目集_')->range('1-10');
    $program->type->range('program,project');
    $program->project->range('0');
    $program->vision->range('rnd');
    $program->gen(10);

    /* Generate releate product to project data. */
    $project2product = zdTable('projectproduct');
    $project2product->project->range('2,4,6,8,10');
    $project2product->product->range('1-50');
    $project2product->branch->range('0');
    $project2product->plan->range('0');
    $project2product->gen(50);

    /* Generate releated team data. */
    $team = zdTable('team');
    $team->type->range('project');
    $team->root->range('2,4,6,8,10');
    $team->gen(5);

}
initData();

/**

title=productTao->getList();
cid=2

- 校验总数         @25
- 校验shadow总数   @25
- 校验line总数     @5
- 校验limit        @3
- 校验noclosed状态 @20
- 校验所属项目集   @5

 */

global $config;

$product = new productTest('admin');

/* Valiate defualt total. */
$result = $product->objectModel->getList(0, 'all', 0, 0, 'all');
r(count($result)) && p('') && e('25');

/* Validate shadow total. */
$result = $product->objectModel->getList(0, 'all', 0, 0, 0);
r(count($result)) && p('') && e('25');

/* Validate line total. */
$result = $product->objectModel->getList(0, 'all', 0, 1, 0);
r(count($result)) && p('') && e('5');

/* Validate limit. */
$result = $product->objectModel->getList(0, 'all', 3, 1, 0);
r(count($result)) && p('') && e('3');

/* Validate noclosed status. */
$result = $product->objectModel->getList(0, 'noclosed', 0, 0, 0);
r(count($result)) && p('') && e('20');

/* Validate noclosed status. */
$result = $product->objectModel->getList(1, 'noclosed', 0, 0, 0);
r(count($result)) && p('') && e('5');
