UPDATE zt_demand SET status = 'active' WHERE status in ('launched','distributed','pass');
UPDATE zt_story  SET status = 'active' WHERE status in ('launched','developing');

ALTER TABLE `zt_approvalnode` ADD `forwardTo` char(30) NOT NULL DEFAULT '' AFTER `extra`;
