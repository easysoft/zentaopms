<?php
global $config;

if($config->vision == 'gitfox')
{
    unset($lang->admin->menuList->switch);
    unset($lang->admin->menuList->workflow);
    unset($lang->admin->menuList->approvalFlow);
    unset($lang->admin->menuList->feature);
    unset($lang->admin->menuList->message);
    unset($lang->admin->menuList->extension);
    unset($lang->admin->menuList->dev);
    unset($lang->admin->menuList->convert);
    unset($lang->admin->menuList->adminregister);

    /* GitFox独立版不展示重建索引菜单。*/
    unset($lang->admin->menuList->system['subMenu']['buildindex']);
    unset($lang->admin->menuList->system['menuOrder']['50']);

    $lang->admin->menuList->branchType['name']  = $lang->devops->branchType;
    $lang->admin->menuList->branchType['desc']  = 'Manage branch types and naming rules.';
    $lang->admin->menuList->branchType['link']  = 'repobranchtype|browse|';
    $lang->admin->menuList->branchType['icon']  = 'code-fork';
    $lang->admin->menuList->branchType['bg']    = 'primary';
    $lang->admin->menuList->branchType['order'] = 75;

    $lang->admin->menuList->service['name']  = $lang->provider->common;
    $lang->admin->menuList->service['desc']  = 'Manage repository services.';
    $lang->admin->menuList->service['link']  = 'provider|browse|';
    $lang->admin->menuList->service['icon']  = 'cog';
    $lang->admin->menuList->service['bg']    = 'primary';
    $lang->admin->menuList->service['order'] = 80;

    $lang->admin->menuList->runner['name']  = $lang->runner->common;
    $lang->admin->menuList->runner['desc']  = 'Manage runners.';
    $lang->admin->menuList->runner['link']  = 'runner|browse|';
    $lang->admin->menuList->runner['icon']  = 'run-alt';
    $lang->admin->menuList->runner['bg']    = 'primary';
    $lang->admin->menuList->runner['order'] = 85;

    $lang->admin->zentaoUsed = 'You are using Gitfox';
    $lang->admin->zentaoInfo = 'Gitfox Information';

    $lang->admin->officialAccount = 'Official Service Account';
    $lang->admin->followUs        = 'Follow Us';
    $lang->admin->followUsContent = 'Stay updated on Gitfox news and access help and support at any time.';
}
