<?php
global $lang,$app;

$config->tutorial->guides = array();

/* Basic tutorial. */
$config->tutorial->guides[$accountManage->name]                 = $accountManage;
$config->tutorial->guides[$productManage->basic->name]          = $productManage->basic;
$config->tutorial->guides[$scrumProjectManage->basic->name]     = $scrumProjectManage->basic;
$config->tutorial->guides[$waterfallProjectManage->basic->name] = $waterfallProjectManage->basic;
$config->tutorial->guides[$kanbanProjectManage->basic->name]    = $kanbanProjectManage->basic;
$config->tutorial->guides[$taskManage->name]                    = $taskManage;
$config->tutorial->guides[$testManage->name]                    = $testManage;

/* Advance tutorial. */
$config->tutorial->guides[$programManage->name]                   = $programManage;
$config->tutorial->guides[$productManage->advance->name]          = $productManage->advance;
$config->tutorial->guides[$scrumProjectManage->advance->name]     = $scrumProjectManage->advance;
$config->tutorial->guides[$waterfallProjectManage->advance->name] = $waterfallProjectManage->advance;
$config->tutorial->guides[$kanbanProjectManage->advance->name]    = $kanbanProjectManage->advance;
if($app->config->edition != 'open') $config->tutorial->guides[$feedbackManage->name] = $feedbackManage;
