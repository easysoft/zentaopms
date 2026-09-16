<?php
$lang->block->summary->welcome = 'GitFox has been with you for %s';

$lang->block->welcome->reviewByMe = 'Related to Me';

$lang->block->welcome->reviewList['reviewByMe']      = 'My Pending Reviews';
$lang->block->welcome->reviewList['myCreatedReview'] = 'Reviews I Created';
$lang->block->welcome->reviewList['myIssue']         = 'Issues to Resolve';

$lang->block->moduleList['ppm'] = $lang->devops->ppm;

$lang->block->modules['ppm'] = new stdclass();
$lang->block->modules['ppm']->availableBlocks['reviewbyme'] = 'My Pending Reviews';

$lang->block->titleList['reviewbyme'] = 'My Pending Reviews';

$lang->block->reviewbyme = new stdclass();
$lang->block->reviewbyme->featureBar['all']      = 'All';
$lang->block->reviewbyme->featureBar['pending']  = 'Pending Review';
$lang->block->reviewbyme->featureBar['reviewed'] = 'Reviewed';
