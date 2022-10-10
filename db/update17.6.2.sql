ALTER TABLE `zt_kanban` ADD `colWidth` smallint(4) NOT NULL DEFAULT '264' AFTER `fluidBoard`;
ALTER TABLE `zt_kanban` ADD `minColWidth` smallint(4) NOT NULL DEFAULT '180' AFTER `colWidth`;
ALTER TABLE `zt_kanban` ADD `maxColWidth` smallint(4) NOT NULL DEFAULT '384' AFTER `minColWidth`;
ALTER TABLE `zt_project` ADD `colWidth` smallint(4) NOT NULL DEFAULT '264' AFTER `fluidBoard`;
ALTER TABLE `zt_project` ADD `minColWidth` smallint(4) NOT NULL DEFAULT '180' AFTER `colWidth`;
ALTER TABLE `zt_project` ADD `maxColWidth` smallint(4) NOT NULL DEFAULT '384' AFTER `minColWidth`;
