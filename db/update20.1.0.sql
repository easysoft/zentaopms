UPDATE `zt_grouppriv` SET module='researchtask', `method`='create'         WHERE module='marketresearch' AND `method`='createTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='edit'           WHERE module='marketresearch' AND `method`='editTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='close'          WHERE module='marketresearch' AND `method`='closeTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='start'          WHERE module='marketresearch' AND `method`='startTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='finish'         WHERE module='marketresearch' AND `method`='finishTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='delete'         WHERE module='marketresearch' AND `method`='deleteTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='cancel'         WHERE module='marketresearch' AND `method`='cancelTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='activate'       WHERE module='marketresearch' AND `method`='activateTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='assignTo'       WHERE module='marketresearch' AND `method`='taskAssignTo';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='view'           WHERE module='marketresearch' AND `method`='viewTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='batchCreate'    WHERE module='marketresearch' AND `method`='batchCreateTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='recordWorkhour' WHERE module='marketresearch' AND `method`='recordTaskEstimate';

UPDATE `zt_grouppriv` SET module='marketresearch', `method`='task'   WHERE module='marketresearch' AND `method`='stage';

REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`) VALUES
('all', 'process', 'scrumClassify', 'support', '支持过程', '1'),
('all', 'process', 'scrumClassify', 'engineering', '工程支持', '1'),
('all', 'process', 'scrumClassify', 'project', '项目管理', '1'),
('all', 'process', 'agileplusClassify', 'support', '支持过程', '1'),
('all', 'process', 'agileplusClassify', 'engineering', '工程支持', '1'),
('all', 'process', 'agileplusClassify', 'project', '项目管理', '1'),
('all', 'process', 'waterfallplusClassify', 'support', '支持过程', '1'),
('all', 'process', 'waterfallplusClassify', 'engineering', '工程支持', '1'),
('all', 'process', 'waterfallplusClassify', 'project', '项目管理', '1');
