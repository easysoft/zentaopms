#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
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

title=productModel->getList();
cid=1
pid=1

*/

$product = new productTest('admin');

$t_numproject = array('0','1', '2', '11');

r($product->getAllProductsCount($t_numproject[1]))                   && p() && e('5');   // 返回项目集1下的产品数量
r($product->getAllProductsCount($t_numproject[0]))                   && p() && e('25'); // 测试传入programID=0的情况
r($product->getAllProductsCount($t_numproject[3]))                   && p() && e('0');   // 传入不存在的项目集
r($product->getNoclosedProductsCount($t_numproject[1]))              && p() && e('5');   // 返回项目集1下的未关闭的产品数量
r($product->getNoclosedProductsCount($t_numproject[0]))              && p() && e('20');  // 获取所有的未关闭的产品数量
r($product->getClosedProductsCount($t_numproject[1]))                && p() && e('0');   // 返回项目集1下的关闭了的产品数量
r($product->getClosedProductsCount($t_numproject[0]))                && p() && e('5');  // 返回所有的未关闭的产品数量
r($product->getInvolvedProductsCount($t_numproject[1]))              && p() && e('5');   // 返回项目集1下的与当前用户有关系的产品数量
r($product->countProductsByLine($t_numproject[1], $t_numproject[1])) && p() && e('5');   // 返回项目集1下产品线1的产品数量
