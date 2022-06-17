DELETE from zt_report where `code` = 'product-invest';
DELETE from zt_report where `code` = 'effort';

INSERT INTO `zt_report`(`code`, `name`, `module`, `sql`, `vars`, `langs`, `params`, `step`, `desc`, `addedBy`, `addedDate`) VALUES
('effort', '{\"zh-cn\":\"\\u65e5\\u5fd7\\u6c47\\u603b\\u8868\",\"zh-tw\":\"\\u65e5\\u8a8c\\u532f\\u7e3d\\u8868\",\"en\":\"Effort Summary\"}', ',staff', 'select t1.account,t1.consumed,t1.`date`,if($dept=\'0\',0,t2.dept) as dept from TABLE_EFFORT as t1 left join TABLE_USER as t2 on t1.account=t2.account where t1.`deleted`=\'0\' and if($startDate=\'\',1,t1.`date`>=$startDate) and if($endDate=\'\',1,t1.`date`<=$endDate) having if($dept=\'0\', dept=$dept,dept in (select id from TABLE_DEPT where concat(\',\',path,\',\')  like concat(\'%,\',$dept,\',%\')))  order by `date` asc', '{\"varName\":[\"dept\",\"startDate\",\"endDate\"],\"showName\":[\"\\u90e8\\u95e8\",\"\\u8d77\\u59cb\\u65f6\\u95f4\",\"\\u7ed3\\u675f\\u65f6\\u95f4\"],\"requestType\":[\"select\",\"date\",\"date\"],\"selectList\":[\"dept\",\"user\",\"user\"],\"default\":[\"\",\"$MONTHBEGIN\",\"$MONTHEND\"]}', '{\"date\":{\"zh-cn\":\"\\u65e5\\u671f\",\"zh-tw\":\"\\u65e5\\u671f\",\"en\":\"Date\"},\"consumed\":{\"zh-cn\":\"\\u6d88\\u8017\\u5de5\\u65f6\",\"zh-tw\":\"\\u6d88\\u8017\\u5de5\\u65f6\",\"en\":\"Cost\"},\"account\":{\"zh-cn\":\"\\u767b\\u8bb0\\u4eba\",\"zh-tw\":\"\\u767b\\u8bb0\\u4eba\",\"en\":\"Owner\"}}', '{\"group1\":\"account\",\"isUser\":{\"group1\":[\"1\"]},\"group2\":\"\",\"reportField\":[\"date\"],\"reportType\":[\"sum\"],\"sumAppend\":[\"consumed\"]}', 2, '{\"zh-cn\":\"\\u67e5\\u770b\\u67d0\\u4e2a\\u65f6\\u95f4\\u6bb5\\u5185\\u7684\\u65e5\\u5fd7\\u60c5\\u51b5\\uff0c\\u53ef\\u4ee5\\u6309\\u7167\\u90e8\\u95e8\\u9009\\u62e9\\u3002\",\"zh-tw\":\"\\u67e5\\u770b\\u67d0\\u500b\\u6642\\u9593\\u6bb5\\u5167\\u7684\\u65e5\\u8a8c\\u60c5\\u6cc1\\uff0c\\u53ef\\u4ee5\\u6309\\u7167\\u90e8\\u9580\\u9078\\u64c7\\u3002\",\"en\":\"You can view the logs of a certain period by department.\"}', 'admin', '2015-07-27 13:53:32');

ALTER TABLE `zt_review` ADD docVersion smallint(6) NOT NULL AFTER `doc`;

DELETE FROM `zt_workflowaction` WHERE `module`='story'   AND `action`='browse';
DELETE FROM `zt_workflowaction` WHERE `module`='task'    AND `action`='browse';
DELETE FROM `zt_workflowaction` WHERE `module`='build'   AND `action`='browse';

ALTER TABLE `zt_projectproduct` MODIFY COLUMN `plan` varchar(255) NOT NULL;

UPDATE `zt_project` SET status = 'closed' WHERE status='done';
