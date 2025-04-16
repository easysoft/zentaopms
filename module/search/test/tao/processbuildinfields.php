#!/usr/bin/env php
<?php

/**
 * Test searchTao->processBuildinFields()
 *
 * @title=测试 searchTao->processBuildinFields();
 * @timeout=0
 * @cid=1
 */

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

$search = new searchTest();

$moduleList = array('projectStory', 'bug', 'product', 'testcase', 'caselib');

r($search->processBuildinFieldsTest($moduleList[0])) && p('module;fields;maxCount', ';') && e('story;title,id,keywords,status,pri,module,stage,grade,plan,estimate,source,sourceNote,fromBug,category,openedBy,reviewedBy,result,assignedTo,closedBy,lastEditedBy,mailto,closedReason,version,openedDate,reviewedDate,assignedDate,closedDate,lastEditedDate,activatedDate;500'); // projectstory
r($search->processBuildinFieldsTest($moduleList[1])) && p('module;fields;maxCount', ';') && e('bug;title,module,keywords,steps,assignedTo,resolvedBy,status,confirmed,story,project,branch,plan,id,execution,severity,pri,type,os,browser,resolution,activatedCount,toTask,toStory,openedBy,closedBy,lastEditedBy,mailto,openedBuild,resolvedBuild,openedDate,assignedDate,resolvedDate,closedDate,lastEditedDate,deadline,activatedDate;500'); // bug
r($search->processBuildinFieldsTest($moduleList[2])) && p('module;fields;maxCount', ';') && e('story;title,id,keywords,status,pri,module,stage,grade,plan,estimate,source,sourceNote,fromBug,category,openedBy,reviewedBy,result,assignedTo,closedBy,lastEditedBy,mailto,closedReason,version,openedDate,reviewedDate,assignedDate,closedDate,lastEditedDate,activatedDate;500'); // product
r($search->processBuildinFieldsTest($moduleList[3])) && p('module;fields;maxCount', ';') && e('testcase;title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene;500'); // testcase
r($search->processBuildinFieldsTest($moduleList[4])) && p('module;fields;maxCount', ';') && e('caselib;title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,stage,module,pri,openedDate,lastEditedDate;500'); // caselib
