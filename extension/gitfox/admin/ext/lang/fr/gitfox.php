<?php
global $config;

if($config->vision == 'gitfox')
{
    unset($lang->admin->menuList->workflow);
    unset($lang->admin->menuList->approvalFlow);

    $lang->admin->menuList->devopsConfig['name']  = 'DevOps Config';
    $lang->admin->menuList->devopsConfig['desc']  = 'Configure branch types, services and build runners.';
    $lang->admin->menuList->devopsConfig['order'] = 75;

    $lang->admin->menuList->devopsConfig['subMenu']['branchType'] = array('link' => "{$lang->devops->branchType}|repobranchtype|browse|", 'alias' => 'create,edit,delete,import');
    $lang->admin->menuList->devopsConfig['subMenu']['service']    = array('link' => "{$lang->provider->common}|provider|browse|", 'alias' => 'create,edit');
    $lang->admin->menuList->devopsConfig['subMenu']['runner']     = array('link' => "{$lang->runner->common}|runner|browse|");

    $lang->admin->menuList->devopsConfig['menuOrder'][5]  = 'branchType';
    $lang->admin->menuList->devopsConfig['menuOrder'][10] = 'service';
    $lang->admin->menuList->devopsConfig['menuOrder'][15] = 'runner';
}
