<?php
global $lang, $config, $app;
$app->loadLang('todo');
$app->loadLang('score');
$app->loadLang('task');
$app->loadLang('story');
$app->loadLang('requirement');
$app->loadLang('epic');
$app->loadLang('bug');
$app->loadLang('doc');
$app->loadLang('testtask');
$app->loadLang('testcase');
$app->loadLang('product');
$app->loadLang('project');
$app->loadLang('execution');
$app->loadModuleConfig('testtask');
$app->loadModuleConfig('testcase');
$app->loadModuleConfig('company');
$app->loadModuleConfig('project');

$isEn  = $app->getClientLang() == 'en';
$space = '';

include __DIR__ . '/dtable/todo.php';
include __DIR__ . '/dtable/score.php';
include __DIR__ . '/dtable/task.php';
include __DIR__ . '/dtable/requirement.php';
include __DIR__ . '/dtable/epic.php';
include __DIR__ . '/dtable/story.php';
include __DIR__ . '/dtable/bug.php';
include __DIR__ . '/dtable/testtask.php';
include __DIR__ . '/dtable/testcase.php';
include __DIR__ . '/dtable/audit.php';
include __DIR__ . '/dtable/execution.php';
include __DIR__ . '/dtable/doc.php';
include __DIR__ . '/dtable/team.php';
include __DIR__ . '/dtable/project.php';
