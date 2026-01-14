#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getLinkedCommits();
timeout=0
cid=18541

- 执行storyTest模块的getLinkedCommitsTest方法，参数是1, array  @0
- 执行storyTest模块的getLinkedCommitsTest方法，参数是999, array  @0
- 执行storyTest模块的getLinkedCommitsTest方法，参数是1, array  @0
- 执行storyTest模块的getLinkedCommitsTest方法，参数是0, array  @0
- 执行storyTest模块的getLinkedCommitsTest方法，参数是1, array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zendata('repohistory')->loadYaml('repohistory_getlinkedcommits', false, 2)->gen(5);
zendata('relation')->loadYaml('relation_getlinkedcommits', false, 2)->gen(5);
zendata('story')->loadYaml('story_getlinkedcommits', false, 2)->gen(5);

su('admin');

$storyTest = new storyModelTest();

r($storyTest->getLinkedCommitsTest(1, array('abc123', 'def456'))) && p() && e('0');
r($storyTest->getLinkedCommitsTest(999, array('abc123'))) && p() && e('0');
r($storyTest->getLinkedCommitsTest(1, array())) && p() && e('0');
r($storyTest->getLinkedCommitsTest(0, array('abc123'))) && p() && e('0');
r($storyTest->getLinkedCommitsTest(1, array('abc123', 'def456', 'ghi789'))) && p() && e('0');