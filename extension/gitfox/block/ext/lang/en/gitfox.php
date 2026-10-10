<?php
$lang->block->summary->welcome = 'gitfox has been with you for %s';

$lang->block->welcome->reviewByMe = 'Related to Me';

$lang->block->welcome->reviewList['reviewByMe']      = 'My Pending Reviews';
$lang->block->welcome->reviewList['myCreatedReview'] = 'Reviews I Created';
$lang->block->welcome->reviewList['myIssue']         = 'Issues to Resolve';

$lang->block->modules['ppm'] = new stdclass();
$lang->block->modules['ppm']->availableBlocks['reviewbyme']        = 'My Pending Reviews';
$lang->block->modules['ppm']->availableBlocks['reviewcreatedbyme'] = 'Reviews I Created';

$lang->block->titleList['reviewbyme']        = 'My Pending Reviews';
$lang->block->titleList['reviewcreatedbyme'] = 'Reviews I Created';

$lang->block->reviewbyme = new stdclass();
$lang->block->reviewbyme->featureBar['all']      = 'All';
$lang->block->reviewbyme->featureBar['pending']  = 'Pending Review';
$lang->block->reviewbyme->featureBar['reviewed'] = 'Reviewed';

$lang->block->reviewcreatedbyme = new stdclass();
$lang->block->reviewcreatedbyme->featureBar['all']    = 'All';
$lang->block->reviewcreatedbyme->featureBar['opened'] = 'Opened';
$lang->block->reviewcreatedbyme->featureBar['merged'] = 'Merged';
$lang->block->reviewcreatedbyme->featureBar['closed'] = 'Closed';

$lang->block->teamachievement->commits        = 'Commits';
$lang->block->teamachievement->createdReviews = 'Created Reviews';
$lang->block->teamachievement->mergedReviews  = 'Merged Reviews';
$lang->block->teamachievement->resolvedIssues = 'Resolved Issues';

$lang->block->zentaoDynamic              = 'gitfox News';
$lang->block->titleList['zentaodynamic'] = 'gitfox News';

$lang->block->moduleList = array
(
    'ppm'             => $lang->devops->ppm,
    'welcome'         => $lang->block->welcome->common,
    'zentaodynamic'   => $lang->block->zentaoDynamic,
    'teamachievement' => $lang->block->teamAchievement,
    'dynamic'         => $lang->block->dynamic,
    'html'            => $lang->block->html
);

$lang->block->default['full']['my'] = array
(
    array('title' => 'Welcome',              'module' => 'welcome',         'code' => 'welcome',          'width' => '2'),
    array('title' => 'My Pending Reviews',   'module' => 'ppm',             'code' => 'reviewbyme',       'width' => '2', 'params' => array('count' => '20')),
    array('title' => 'Reviews I Created',    'module' => 'ppm',             'code' => 'reviewcreatedbyme','width' => '2', 'params' => array('count' => '20')),
    array('title' => 'gitfox News',          'module' => 'zentaodynamic',   'code' => 'zentaodynamic',    'width' => '1'),
    array('title' => 'Recents',              'module' => 'dynamic',         'code' => 'dynamic',          'width' => '1'),
    array('title' => 'Team Achievements',    'module' => 'teamachievement', 'code' => 'teamachievement',  'width' => '1')
);
