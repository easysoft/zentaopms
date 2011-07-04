-- 2011-06-13, fix the error of testTask and testCase.
UPDATE `zt_groupPriv` SET `method` = 'testTask' WHERE `method`='testtask';
UPDATE `zt_groupPriv` SET `method` = 'testCase' WHERE `method`='testcase';

-- 2011-06-30, add product and project field to action. 
ALTER TABLE `zt_action` ADD `product` MEDIUMINT NOT NULL AFTER `objectID` ,
ADD `project` MEDIUMINT NOT NULL AFTER `product` ;

UPDATE zt_action, zt_story SET 
    zt_action.product = zt_story.product  WHERE
    zt_action.objectID = zt_story.id AND 
    zt_action.objectType = 'story';

UPDATE zt_action, zt_projectStory SET 
    zt_action.project = zt_projectStory.project WHERE 
    zt_action.objectID = zt_projectStory.story AND 
    zt_action.objectType = 'story';

UPDATE zt_action, zt_bug SET 
    zt_action.product = zt_bug.product, 
    zt_action.project = zt_bug.project WHERE 
    zt_action.objectID = zt_bug.id AND 
    zt_action.objectType = 'bug';

UPDATE zt_action, zt_build SET 
    zt_action.product = zt_build.product, 
    zt_action.project = zt_build.project WHERE 
    zt_action.objectID = zt_build.id AND 
    zt_action.objectType = 'build';

UPDATE zt_action, zt_case SET 
    zt_action.product = zt_case.product WHERE
    zt_action.objectID = zt_case.id AND 
    zt_action.objectType = 'case';

UPDATE zt_action, zt_doc SET 
    zt_action.product = zt_doc.product,
    zt_action.project = zt_doc.project WHERE
    zt_action.objectID = zt_doc.id AND 
    zt_action.objectType = 'doc';

UPDATE zt_action SET product = objectID WHERE objectType = 'product';
UPDATE zt_action SET project = objectID WHERE objectType = 'project';

UPDATE zt_action, zt_productPlan SET 
    zt_action.product = zt_productPlan.product WHERE
    zt_action.objectID = zt_productPlan.id AND 
    zt_action.objectType = 'productplan';

UPDATE zt_action, zt_release, zt_build SET 
    zt_action.product = zt_release.product, 
    zt_action.project = zt_build.project WHERE 
    zt_action.objectID = zt_release.id AND 
    zt_release.build   = zt_build.id AND
    zt_action.objectType = 'release';

UPDATE zt_action, zt_task SET 
    zt_action.project = zt_task.project WHERE
    zt_action.objectID = zt_task.id AND 
    zt_action.objectType = 'task';

UPDATE zt_action, zt_testTask SET 
    zt_action.project = zt_testTask.project, 
    zt_action.product = zt_testTask.product WHERE
    zt_action.objectID = zt_testTask.id AND 
    zt_action.objectType = 'testtask';

-- 2011-07-04 add type field to extension.
ALTER TABLE `zt_extension` ADD `type` VARCHAR( 20 ) NOT NULL DEFAULT 'extension' AFTER `license` ;
