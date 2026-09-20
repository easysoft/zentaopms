<?php
if($config->vision == 'gitfox')
{
    /* 后台设置区块的卡片分别对应 repobranchtype、provider、runner 页面。 */
    $config->admin->menuGroup['branchType'] = array('repobranchtype');
    $config->admin->menuGroup['service']    = array('provider');
    $config->admin->menuGroup['runner']     = array('runner');
}
