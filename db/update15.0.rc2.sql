DELETE FROM zt_cron WHERE command='moduleName=weekly&methodName=computeWeekly';
ALTER TABLE zt_story drop column `project`;
