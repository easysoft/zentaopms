CREATE INDEX `status_createdDate` ON `zt_queue`(`status`, `createdDate`);
CREATE INDEX `cron_createdDate` ON `zt_queue`(`cron`, `createdDate`);
CREATE INDEX `status_deleted` ON `zt_measqueue`(`status`, `deleted`);
