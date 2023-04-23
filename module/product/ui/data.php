<?php

$program6 = new stdClass();
$program6->id = '6';
$program6->parent = '0';
$program6->name = '企业管理';

$program7 = new stdClass();
$program7->id = '7';
$program7->parent = '6';
$program7->name = '企业系统管理';

$program8 = new stdClass();
$program8->id = '8';
$program8->parent = '6';
$program8->name = '测试项目';

$program15 = new stdClass();
$program15->id = '15';
$program15->parent = '0';
$program15->name = '测试项目集一';

$program16 = new stdClass();
$program16->id = '16';
$program16->parent = '15';
$program16->name = 'scrum';

$programs = array($program6, $program7, $program8, $program15, $program16);
