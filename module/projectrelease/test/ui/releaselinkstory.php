#!/usr/bin/env php
<?php

/**
title=项目发布关联和移除研发需求
timeout=0
cid=73
*/
chdir(__DIR__);
include '../lib/releaselinkstory.ui.class.php';

zendata('story')->loadYaml('story', false, 1)->gen(5);
zendata('release')->loadYaml('projectrelease', false, 1)->gen(1);
zendata('project')->loadYaml('project', false, 1)->gen(1);
zendata('storyspec')->loadYaml('storyspec', false, 1)->gen(5);
zendata('projectproduct')->loadYaml('projectproduct', false, 1)->gen(1);

$tester = new releaseLinkStoryTester();
