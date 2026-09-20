<?php
$lang->block->summary->welcome = 'gitfox已陪伴您%s';

$lang->block->welcome->reviewByMe = '与我相关';

$lang->block->welcome->reviewList['reviewByMe']      = '待我评审';
$lang->block->welcome->reviewList['myCreatedReview'] = '我发起的评审';
$lang->block->welcome->reviewList['myIssue']         = '待我解决的问题';

$lang->block->modules['ppm'] = new stdclass();
$lang->block->modules['ppm']->availableBlocks['reviewbyme']        = '待我评审';
$lang->block->modules['ppm']->availableBlocks['reviewcreatedbyme'] = '我创建的评审';

$lang->block->titleList['reviewbyme']        = '待我评审';
$lang->block->titleList['reviewcreatedbyme'] = '我创建的评审';

$lang->block->reviewbyme = new stdclass();
$lang->block->reviewbyme->featureBar['all']      = '全部';
$lang->block->reviewbyme->featureBar['pending']  = '待评审';
$lang->block->reviewbyme->featureBar['reviewed'] = '已评审';

$lang->block->reviewcreatedbyme = new stdclass();
$lang->block->reviewcreatedbyme->featureBar['all']    = '全部';
$lang->block->reviewcreatedbyme->featureBar['opened'] = '开放中';
$lang->block->reviewcreatedbyme->featureBar['merged'] = '已合并';
$lang->block->reviewcreatedbyme->featureBar['closed'] = '已关闭';

$lang->block->teamachievement->commits        = '代码提交次数';
$lang->block->teamachievement->createdReviews = '发起的评审数';
$lang->block->teamachievement->mergedReviews  = '合并的评审数';
$lang->block->teamachievement->resolvedIssues = '解决的问题数';

$lang->block->zentaoDynamic              = 'gitfox动态';
$lang->block->titleList['zentaodynamic'] = 'gitfox动态';

$lang->block->moduleList = array
(
    'ppm'             => $lang->devops->ppm,
    'welcome'         => $lang->block->welcome->common,
    'zentaodynamic'   => $lang->block->zentaoDynamic,
    'teamachievement' => $lang->block->teamAchievement,
    'assigntome'      => $lang->block->assignToMe,
    'dynamic'         => $lang->block->dynamic,
    'html'            => $lang->block->html
);

$lang->block->default['full']['my'] = array
(
    array('title' => '欢迎总览',     'module' => 'welcome',         'code' => 'welcome',          'width' => '2'),
    array('title' => '待我评审',     'module' => 'ppm',             'code' => 'reviewbyme',       'width' => '2', 'params' => array('count' => '20')),
    array('title' => '我创建的评审', 'module' => 'ppm',             'code' => 'reviewcreatedbyme','width' => '2', 'params' => array('count' => '20')),
    array('title' => 'gitfox动态',   'module' => 'zentaodynamic',   'code' => 'zentaodynamic',    'width' => '1'),
    array('title' => '最新动态',     'module' => 'dynamic',         'code' => 'dynamic',          'width' => '1'),
    array('title' => '团队成就',     'module' => 'teamachievement', 'code' => 'teamachievement',  'width' => '1')
);
