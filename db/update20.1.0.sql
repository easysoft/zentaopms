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
