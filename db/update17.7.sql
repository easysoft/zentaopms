update zt_kanban
set
  colWidth    = if(colWidth < 200, 200, colWidth),
  minColWidth = if(minColWidth < 200, 200, minColWidth),
  maxColWidth = if(maxColWidth <= 200 and minColWidth <= 200, 201, maxColWidth)
where colWidth < 200 or minColWidth < 200 or maxColWidth < 200;

update zt_project
set
  colWidth    = if(colWidth < 200, 200, colWidth),
  minColWidth = if(minColWidth < 200, 200, minColWidth),
  maxColWidth = if(maxColWidth <= 200 and minColWidth <= 200, 201, maxColWidth)
where colWidth < 200 or minColWidth < 200 or maxColWidth < 200;
ALTER TABLE `zt_kanban` ADD `minColWidth` smallint(4) NOT NULL DEFAULT '200' AFTER `colWidth`;
ALTER TABLE `zt_project` ADD `minColWidth` smallint(4) NOT NULL DEFAULT '200' AFTER `colWidth`;
