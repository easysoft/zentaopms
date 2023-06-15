INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`) VALUES ('0','*','*','*','*','moduleName=misc&methodName=cleanCache', '清理缓存文件','zentao', 1, 'normal');

ALTER TABLE `zt_product`
ADD `draftStories` mediumint(8) NOT NULL DEFAULT '0' AFTER `reviewer`,
ADD `activeStories` mediumint(8) NOT NULL DEFAULT '0' AFTER `draftStories`,
ADD `changingStories` mediumint(8) NOT NULL DEFAULT '0' AFTER `activeStories`,
ADD `reviewingStories` mediumint(8) NOT NULL DEFAULT '0' AFTER `changingStories`,
ADD `finishedStories` mediumint NOT NULL DEFAULT '0' AFTER `reviewingStories`,
ADD `closedStories` mediumint(8) NOT NULL DEFAULT '0' AFTER `finishedStories`,
ADD `totalStories` mediumint(8) NOT NULL DEFAULT '0' AFTER `closedStories`,
ADD `unresolvedBugs` mediumint(8) NOT NULL DEFAULT '0' AFTER `totalStories`,
ADD `closedBugs` mediumint(8) NOT NULL DEFAULT '0' AFTER `unresolvedBugs`,
ADD `fixedBugs` mediumint(8) NOT NULL DEFAULT '0' AFTER `closedBugs`,
ADD `totalBugs` mediumint(8) NOT NULL DEFAULT '0' AFTER `fixedBugs`,
ADD `plans` mediumint(8) NOT NULL DEFAULT '0' AFTER `totalBugs`,
ADD `releases` mediumint(8) NOT NULL DEFAULT '0' AFTER `plans`;

UPDATE `zt_pivot` SET `sql` = 'SELECT \n  t1.name AS  \'产品\', \n  IFNULL(t2.name, \'/\') AS \'一级项目集\', \n  IFNULL(t3.name, \'/\') AS \'产品线\', \n  IFNULL(t6.exfixedstorys, 0) AS \'研发完成需求数\', \n  round(IFNULL(t6.exfixedstorysmate, 0),3) AS \'研发完成需求规模数\',\n  IFNULL(t8.storycases, 0) AS \'需求用例数\',\n  round(storycases/exfixedstorysmate,3) AS \'用例密度\',\n  round(IFNULL(t10.casestorys/t6.exfixedstorys,0),3) AS \'用例覆盖率\',\n  IFNULL(t7.bug, 0) AS \'Bug数\',\n  IFNULL(t7.effbugs, 0) AS \'有效Bug数\',\n  IFNULL(t7.pri12bugs, 0) AS \'优先级为1，2的Bug数\',\n  round(bug/exfixedstorysmate,3) AS \'Bug密度\',\n  IFNULL(t7.fixedbugs, 0) AS \'修复Bug数\',\n  round(t7.fixedbugs/bug,3) \'Bug修复率\'\nFROM \n  zt_product AS t1 \n  LEFT JOIN zt_project AS t2 ON t1.program = t2.id AND t2.type = \'program\' AND t2.grade = 1 \n  LEFT JOIN zt_module AS t3 ON t1.line = t3.id AND t3.type = \'line\' \n  LEFT JOIN (\n  SELECT\n  product, \n  count(id) exfixedstorys, \n  sum(estimate) exfixedstorysmate\n  FROM zt_story \n  WHERE deleted = \'0\'  and (stage in (\'developed\',\'testing\',\'verfied\',\'released\') or (status=\'closed\' and closedReason=\'done\'))\n  GROUP BY product) AS t6 ON t1.id = t6.product \n  LEFT JOIN (SELECT product, COUNT(id)  AS bug,\n  SUM(case when  resolution in (\'fixed\',\'postponed\') or status=\'active\' then 1 else 0 end) effbugs, \n  SUM(CASE WHEN  resolution=\'fixed\' then 1 else 0 end) fixedbugs,\n  SUM(CASE WHEN severity in (1,2) then 1 else 0 end) pri12bugs\n  FROM zt_bug WHERE deleted = \'0\' GROUP BY product) AS t7 ON t1.id = t7.product \n  LEFT JOIN (SELECT product, COUNT(id) AS storycases FROM zt_case WHERE deleted=\'0\' GROUP BY product) AS t8 ON t1.id=t8.product\n  LEFT JOIN (\n   select \n   t9.product, \n   count(t9.story) casestorys\n   from(\n     SELECT \n     zt_case.product,zt_case.story \n     FROM zt_case \n     left join zt_story\n     on zt_case.story=zt_story.id\n     WHERE zt_case.deleted=\'0\' and zt_case.story !=\'0\' and zt_story.deleted=\'0\' and  (zt_story.stage in (\'developed\',\'testing\',\'verfied\',\'released\') or (zt_story.status=\'closed\' and zt_story.closedReason=\'done\'))\n     GROUP BY product, story) t9\n     group by product) t10\n     on t1.id=t10.product\nWHERE t1.deleted = \'0\' AND t1.status != \'closed\' AND t1.shadow = \'0\'AND t1.vision = \'rnd\'\nORDER BY t1.order'
WHERE id = 1002;
UPDATE `zt_chart` SET `settings` = '{\"value\": {\"type\": \"agg\", \"field\": \"taskleft\", \"agg\": \"sum\"}, \"title\": {\"type\": \"text\", \"name\": \"\"}, \"type\": \"value\"}'
WHERE id = 10112;
