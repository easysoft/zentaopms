<?php
global $lang,$app;

$config->tutorial->guides = array();

if($app->config->vision === 'rnd')
{
    /* Starter tutorial. */
    $config->tutorial->guides[$starter->name] = $starter;

    /* Basic tutorial. */
    $config->tutorial->guides[$accountManage->name]             = $accountManage;
    $config->tutorial->guides[$productManage->basic->name]      = $productManage->basic;
    $config->tutorial->guides[$scrumProjectManage->basic->name] = $scrumProjectManage->basic;
    if($config->systemMode != 'light') $config->tutorial->guides[$waterfallProjectManage->basic->name] = $waterfallProjectManage->basic;
    $config->tutorial->guides[$kanbanProjectManage->basic->name] = $kanbanProjectManage->basic;
    $config->tutorial->guides[$taskManage->name] = $taskManage;
    $config->tutorial->guides[$testManage->name] = $testManage;

    /* Advance tutorial. */
    if($config->systemMode != 'light') $config->tutorial->guides[$programManage->name] = $programManage;
    $config->tutorial->guides[$productManage->advance->name] = $productManage->advance;
    $config->tutorial->guides[$scrumProjectManage->advance->name] = $scrumProjectManage->advance;
    if($config->systemMode != 'light') $config->tutorial->guides[$waterfallProjectManage->advance->name] = $waterfallProjectManage->advance;
    $config->tutorial->guides[$kanbanProjectManage->advance->name] = $kanbanProjectManage->advance;
    if($app->config->edition != 'open') $config->tutorial->guides[$feedbackManage->name] = $feedbackManage;
    $config->tutorial->guides[$docManage->name] = $docManage;
}

if($app->config->vision === 'or')
{
    $config->tutorial->guides[$orTutorial->demandpoolManage->name] = $orTutorial->demandpoolManage;
    $config->tutorial->guides[$orTutorial->marketManage->name]     = $orTutorial->marketManage;
    $config->tutorial->guides[$orTutorial->roadmapManage->name]    = $orTutorial->roadmapManage;
    $config->tutorial->guides[$orTutorial->charterManage->name]    = $orTutorial->charterManage;
}
