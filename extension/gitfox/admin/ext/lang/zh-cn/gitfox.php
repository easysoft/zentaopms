<?php
global $config;

if($config->vision == 'gitfox')
{
    /* 开源版后台样式：去掉付费版的流程/审批流，用 DevOps配置 占位。 */
    unset($lang->admin->menuList->workflow);
    unset($lang->admin->menuList->approvalFlow);

    $lang->admin->menuList->devopsConfig['name']  = 'DevOps配置';
    $lang->admin->menuList->devopsConfig['desc']  = '配置代码库的分支类型、服务与构建执行器。';
    $lang->admin->menuList->devopsConfig['order'] = 75;

    $lang->admin->menuList->devopsConfig['subMenu']['branchType'] = array('link' => "{$lang->devops->branchType}|repobranchtype|browse|", 'alias' => 'create,edit,delete,import');
    $lang->admin->menuList->devopsConfig['subMenu']['service']    = array('link' => "{$lang->provider->common}|provider|browse|", 'alias' => 'create,edit');
    $lang->admin->menuList->devopsConfig['subMenu']['runner']     = array('link' => "{$lang->runner->common}|runner|browse|");

    $lang->admin->menuList->devopsConfig['menuOrder'][5]  = 'branchType';
    $lang->admin->menuList->devopsConfig['menuOrder'][10] = 'service';
    $lang->admin->menuList->devopsConfig['menuOrder'][15] = 'runner';
}
