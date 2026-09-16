<?php
$lang->block->summary->welcome = 'GitFox已陪伴您%s';

$lang->block->welcome->reviewByMe = '与我相关';

$lang->block->welcome->reviewList['reviewByMe']      = '待我评审';
$lang->block->welcome->reviewList['myCreatedReview'] = '我发起的评审';
$lang->block->welcome->reviewList['myIssue']         = '待我解决的问题';

$lang->block->moduleList['ppm'] = $lang->devops->ppm;

$lang->block->modules['ppm'] = new stdclass();
$lang->block->modules['ppm']->availableBlocks['reviewbyme'] = '待我评审';

$lang->block->titleList['reviewbyme'] = '待我评审';

$lang->block->reviewbyme = new stdclass();
$lang->block->reviewbyme->featureBar['all']      = '全部';
$lang->block->reviewbyme->featureBar['pending']  = '待评审';
$lang->block->reviewbyme->featureBar['reviewed'] = '已评审';
