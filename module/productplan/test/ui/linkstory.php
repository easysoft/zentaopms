#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/linkstory.ui.class.php';

zendata('product')->loadYaml('product', false, 2)->gen(10);
zendata('productplan')->loadYaml('productplan', false, 2)->gen(10);

$story = zenData('story');
$story->id->range('1-10');
$story->version->range('1');
$story->title->range('需求1,需求2,需求3,需求4,需求5');
$story->status->range('active');
$story->plan->range('0');
$story->gen(5);

