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

    $lang->admin->menuList->branchType['name']  = $lang->devops->branchType;
    $lang->admin->menuList->branchType['desc']  = '维护代码库分支类型及命名规则。';
    $lang->admin->menuList->branchType['link']  = 'repobranchtype|browse|';
    $lang->admin->menuList->branchType['icon']  = 'code-fork';
    $lang->admin->menuList->branchType['bg']    = 'primary';
    $lang->admin->menuList->branchType['order'] = 75;

    $lang->admin->menuList->service['name']  = $lang->provider->common;
    $lang->admin->menuList->service['desc']  = '配置代码库关联的服务。';
    $lang->admin->menuList->service['link']  = 'provider|browse|';
    $lang->admin->menuList->service['icon']  = 'cog';
    $lang->admin->menuList->service['bg']    = 'primary';
    $lang->admin->menuList->service['order'] = 80;

    $lang->admin->menuList->runner['name']  = $lang->runner->common;
    $lang->admin->menuList->runner['desc']  = '管理流水线执行节点。';
    $lang->admin->menuList->runner['link']  = 'runner|browse|';
    $lang->admin->menuList->runner['icon']  = 'run-alt';
    $lang->admin->menuList->runner['bg']    = 'primary';
    $lang->admin->menuList->runner['order'] = 85;

    $lang->admin->zentaoUsed = '您已使用gitfox';
    $lang->admin->zentaoInfo = 'gitfox信息';

    $lang->admin->officialAccount = '官方服务号';
    $lang->admin->followUs        = '扫码关注服务号';
    $lang->admin->followUsContent = '随时查看gitfox动态，也可获取帮助支持';
}
