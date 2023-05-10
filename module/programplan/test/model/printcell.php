#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->printCell()
cid=1
pid=1

*/

function call($programplan, $col, $plan, $users, $productID)
{
    ob_start();
    $programplan->printCellTest($col, $plan, $users, $productID);
    $result = ob_get_clean();
    $pattern = '/<td[^>]*>(.*?)<\/td>/s';
    preg_match_all($pattern, $result, $matches);
    return $matches;
}

$programplan = new programplanTest();

$colIDList = array('id', 'name', 'percent', 'attribute', 'begin', 'end', 'realBegan', 'realEnd', 'output', 'version', 'editedBy');

$col = new stdClass();
$col->id   = $colIDList[0];
$col->show = true;

$plan = new stdClass();
$plan->id        = '1';
$plan->milestone = true;
$plan->grade     = 1;
$plan->children  = 1;
$plan->name      = '名称';
$plan->percent   = '10';
$plan->attribute = '10';
$plan->begin     = '2023-05-06';
$plan->editedBy  = 'id_desc';

$users = array();

$matches= call($programplan, $col, $plan, $users, 1);
r(isset($matches[1][0]) ? $matches[1][0] : 0) && p() && e('001');

$col->id = $colIDList[1];
$matches = call($programplan, $col, $plan, $users, 1);
$result  = isset($matches[1][0]) && str_contains($matches[1][0], $plan->name)? 1 : 0;
r($result) && p() && e('1');

$col->id = $colIDList[2];
$matches = call($programplan, $col, $plan, $users, 1);
$result  = isset($matches[1][0]) && $matches[1][0] == '10%' ? 1 : 0;
r($result) && p() && e('1');

/* $colIDList key 4-9验证方法一致 */
$col->id = $colIDList[4];
$matches = call($programplan, $col, $plan, $users, 1);
$result  = isset($matches[1][0]) && $matches[1][0] == $plan->begin ? 1 : 0;
r($result) && p() && e('1');

$col->id = $colIDList[10];
$matches = call($programplan, $col, $plan, $users, 1);
$result  = isset($matches[1][0]) && $matches[1][0] == $plan->editedBy ? 1 : 0;
r($result) && p() && e('1');
