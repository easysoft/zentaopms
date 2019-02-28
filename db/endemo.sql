SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `zt_action` (`id`, `objectType`, `objectID`, `product`, `project`, `actor`, `action`, `date`, `comment`, `extra`, `read`) VALUES
(1,	'user',	1,	',0,',	0,	'admin',	'login',	'2017-12-11 18:52:19',	'',	'',	'0'),
(2,	'user',	1,	',0,',	0,	'admin',	'logout',	'2017-12-12 14:43:47',	'',	'',	'0'),
(3,	'user',	2,	',0,',	0,	'thePO',	'login',	'2017-12-12 14:43:59',	'',	'',	'0'),
(4,	'product',	1,	',1,',	0,	'thePO',	'opened',	'2017-12-12 14:44:57',	'',	'',	'0'),
(5,	'productplan',	1,	',1,',	0,	'thePO',	'opened',	'2017-12-12 14:48:38',	'',	'',	'0'),
(6,	'productplan',	2,	',1,',	0,	'thePO',	'opened',	'2017-12-12 14:49:38',	'',	'',	'0'),
(7,	'productplan',	2,	',1,',	0,	'thePO',	'edited',	'2017-12-12 14:50:13',	'',	'',	'0'),
(8,	'productplan',	1,	',1,',	0,	'thePO',	'edited',	'2017-12-12 14:51:01',	'',	'',	'0'),
(9,	'story',	1,	',1,',	0,	'thePO',	'opened',	'2017-12-12 14:58:30',	'',	'',	'0'),
(10,	'story',	2,	',1,',	0,	'thePO',	'opened',	'2017-12-12 14:58:30',	'',	'',	'0'),
(11,	'story',	3,	',1,',	0,	'thePO',	'opened',	'2017-12-12 14:58:30',	'',	'',	'0'),
(12,	'story',	4,	',1,',	0,	'thePO',	'opened',	'2017-12-12 14:58:30',	'',	'',	'0'),
(13,	'story',	5,	',1,',	0,	'thePO',	'opened',	'2017-12-12 14:58:30',	'',	'',	'0'),
(14,	'story',	6,	',1,',	0,	'thePO',	'opened',	'2017-12-12 14:58:30',	'',	'',	'0'),
(15,	'story',	7,	',1,',	0,	'thePO',	'opened',	'2017-12-12 14:58:30',	'',	'',	'0'),
(16,	'story',	8,	',1,',	0,	'thePO',	'opened',	'2017-12-12 14:58:30',	'',	'',	'0'),
(17,	'project',	1,	',1,',	1,	'thePO',	'opened',	'2017-12-12 15:01:14',	'',	'',	'0'),
(18,	'story',	3,	',1,',	1,	'thePO',	'linked2project',	'2017-12-12 15:02:18',	'',	'1',	'0'),
(19,	'story',	2,	',1,',	1,	'thePO',	'linked2project',	'2017-12-12 15:02:18',	'',	'1',	'0'),
(20,	'story',	1,	',1,',	1,	'thePO',	'linked2project',	'2017-12-12 15:02:18',	'',	'1',	'0'),
(21,	'user',	2,	',0,',	0,	'thePO',	'logout',	'2017-12-12 15:02:58',	'',	'',	'0'),
(22,	'user',	3,	',0,',	0,	'pm1',	'login',	'2017-12-12 15:03:09',	'',	'',	'0'),
(23,	'task',	1,	',1,',	1,	'pm1',	'opened',	'2017-12-12 15:05:06',	'',	'',	'0'),
(24,	'task',	2,	',1,',	1,	'pm1',	'opened',	'2017-12-12 15:05:06',	'',	'',	'0'),
(25,	'task',	3,	',1,',	1,	'pm1',	'opened',	'2017-12-12 15:05:06',	'',	'',	'0'),
(26,	'task',	4,	',1,',	1,	'pm1',	'opened',	'2017-12-12 15:05:06',	'',	'',	'0'),
(27,	'task',	1,	',1,',	1,	'pm1',	'started',	'2017-12-12 15:12:17',	'Start tp design and develop',	'',	'0'),
(28,	'task',	1,	',1,',	1,	'pm1',	'recordestimate',	'2017-12-12 15:13:13',	'Finish UI design',	'8',	'0'),
(29,	'task',	2,	',1,',	1,	'pm1',	'started',	'2017-12-12 15:13:25',	'Start<br />',	'',	'0'),
(30,	'task',	3,	',1,',	1,	'pm1',	'started',	'2017-12-12 15:14:25',	'',	'',	'0'),
(31,	'task',	3,	',1,',	1,	'pm1',	'recordestimate',	'2017-12-12 15:14:47',	'Finish',	'60',	'0'),
(32,	'user',	3,	',0,',	0,	'pm1',	'logout',	'2017-12-12 15:18:46',	'',	'',	'0'),
(33,	'user',	1,	',0,',	0,	'admin',	'login',	'2017-12-12 15:18:58',	'',	'',	'0'),
(34,	'user',	10,	',0,',	0,	'admin',	'deleted',	'2017-12-12 15:20:05',	'',	'1',	'0'),
(35,	'user',	1,	',0,',	0,	'admin',	'logout',	'2017-12-12 15:22:11',	'',	'',	'0'),
(36,	'user',	12,	',0,',	0,	'pm1',	'login',	'2017-12-12 15:22:36',	'',	'',	'0'),
(37,	'user',	12,	',0,',	0,	'pm1',	'logout',	'2017-12-12 15:22:48',	'',	'',	'0'),
(38,	'user',	1,	',0,',	0,	'admin',	'login',	'2017-12-12 15:23:02',	'',	'',	'0'),
(39,	'user',	1,	',0,',	0,	'admin',	'logout',	'2017-12-12 15:23:27',	'',	'',	'0'),
(40,	'user',	11,	',0,',	0,	'pm1',	'login',	'2017-12-12 15:23:34',	'',	'',	'0'),
(41,	'build',	1,	',1,',	1,	'pm1',	'opened',	'2017-12-12 15:25:13',	'',	'',	'0'),
(42,	'bug',	1,	',1,',	1,	'pm1',	'opened',	'2017-12-12 15:27:56',	'',	'',	'0'),
(43,	'bug',	2,	',1,',	1,	'pm1',	'opened',	'2017-12-12 15:30:32',	'',	'',	'0'),
(44,	'user',	11,	',0,',	0,	'pm1',	'logout',	'2017-12-12 15:32:26',	'',	'',	'0'),
(45,	'user',	1,	',0,',	0,	'admin',	'login',	'2017-12-12 15:32:41',	'',	'',	'0'),
(46,	'project',	1,	',1,',	1,	'admin',	'started',	'2017-12-12 15:32:54',	'',	'',	'0'),
(47,	'product',	2,	',2,',	0,	'admin',	'opened',	'2017-12-12 23:28:29',	'',	'',	'0'),
(48,	'productplan',	3,	',2,',	0,	'admin',	'opened',	'2017-12-12 23:31:59',	'',	'',	'0'),
(49,	'productplan',	4,	',2,',	0,	'admin',	'opened',	'2017-12-12 23:32:30',	'',	'',	'0'),
(50,	'user',	1,	',0,',	0,	'admin',	'logout',	'2017-12-12 23:33:01',	'',	'',	'0'),
(51,	'user',	2,	',0,',	0,	'thePO',	'login',	'2017-12-12 23:33:10',	'',	'',	'0'),
(52,	'story',	9,	',2,',	0,	'thePO',	'opened',	'2017-12-12 23:40:19',	'',	'',	'0'),
(53,	'story',	10,	',2,',	0,	'thePO',	'opened',	'2017-12-12 23:40:19',	'',	'',	'0'),
(54,	'story',	11,	',2,',	0,	'thePO',	'opened',	'2017-12-12 23:40:19',	'',	'',	'0'),
(55,	'story',	12,	',2,',	0,	'thePO',	'opened',	'2017-12-12 23:40:19',	'',	'',	'0'),
(56,	'story',	13,	',2,',	0,	'thePO',	'opened',	'2017-12-12 23:40:19',	'',	'',	'0'),
(57,	'story',	14,	',2,',	0,	'thePO',	'opened',	'2017-12-12 23:40:19',	'',	'',	'0'),
(58,	'story',	15,	',2,',	0,	'thePO',	'opened',	'2017-12-12 23:40:19',	'',	'',	'0'),
(59,	'user',	2,	',0,',	0,	'thePO',	'logout',	'2017-12-12 23:41:48',	'',	'',	'0'),
(60,	'user',	3,	',0,',	0,	'pm1',	'login',	'2017-12-12 23:41:58',	'',	'',	'0'),
(61,	'project',	2,	',,',	2,	'pm1',	'opened',	'2017-12-12 23:42:53',	'',	'',	'0'),
(62,	'story',	14,	',2,',	2,	'pm1',	'linked2project',	'2017-12-12 23:43:52',	'',	'2',	'0'),
(63,	'story',	13,	',2,',	2,	'pm1',	'linked2project',	'2017-12-12 23:43:52',	'',	'2',	'0'),
(64,	'story',	12,	',2,',	2,	'pm1',	'linked2project',	'2017-12-12 23:43:52',	'',	'2',	'0'),
(65,	'story',	11,	',2,',	2,	'pm1',	'linked2project',	'2017-12-12 23:43:52',	'',	'2',	'0'),
(66,	'story',	10,	',2,',	2,	'pm1',	'linked2project',	'2017-12-12 23:43:52',	'',	'2',	'0'),
(67,	'story',	9,	',2,',	2,	'pm1',	'linked2project',	'2017-12-12 23:43:52',	'',	'2',	'0'),
(68,	'task',	5,	',2,',	2,	'pm1',	'opened',	'2017-12-12 23:45:14',	'',	'',	'0'),
(69,	'task',	6,	',2,',	2,	'pm1',	'opened',	'2017-12-12 23:45:14',	'',	'',	'0'),
(70,	'task',	7,	',2,',	2,	'pm1',	'opened',	'2017-12-12 23:45:14',	'',	'',	'0'),
(71,	'task',	8,	',2,',	2,	'pm1',	'opened',	'2017-12-12 23:45:14',	'',	'',	'0'),
(72,	'task',	9,	',2,',	2,	'pm1',	'opened',	'2017-12-12 23:45:14',	'',	'',	'0'),
(73,	'task',	10,	',2,',	2,	'pm1',	'opened',	'2017-12-12 23:45:14',	'',	'',	'0'),
(74,	'task',	8,	',2,',	2,	'pm1',	'started',	'2017-12-12 23:45:36',	'',	'',	'0'),
(75,	'task',	7,	',2,',	2,	'pm1',	'recordestimate',	'2017-12-12 23:45:53',	'',	'8',	'0'),
(76,	'user',	3,	',0,',	0,	'pm1',	'logout',	'2017-12-12 23:46:28',	'',	'',	'0'),
(77,	'user',	5,	',0,',	0,	'pm1',	'login',	'2017-12-12 23:46:35',	'',	'',	'0'),
(78,	'task',	9,	',2,',	2,	'pm1',	'started',	'2017-12-12 23:46:58',	'',	'',	'0'),
(79,	'task',	5,	',2,',	2,	'pm1',	'started',	'2017-12-12 23:47:03',	'',	'',	'0'),
(80,	'task',	5,	',2,',	2,	'pm1',	'finished',	'2017-12-12 23:47:37',	'Finished general setup at backend',	'',	'0'),
(81,	'user',	5,	',0,',	0,	'pm1',	'logout',	'2017-12-12 23:48:31',	'',	'',	'0'),
(82,	'user',	7,	',0,',	0,	'pm1',	'login',	'2017-12-12 23:48:39',	'',	'',	'0'),
(83,	'build',	2,	',2,',	2,	'pm1',	'opened',	'2017-12-12 23:49:31',	'',	'',	'0'),
(84,	'task',	9,	',2,',	2,	'pm1',	'recordestimate',	'2017-12-12 23:51:14',	'Finished page design.',	'8',	'0'),
(85,	'user',	7,	',0,',	0,	'pm1',	'logout',	'2017-12-12 23:52:27',	'',	'',	'0'),
(86,	'user',	2,	',0,',	0,	'thePO',	'login',	'2017-12-12 23:52:30',	'',	'',	'0'),
(87,	'story',	10,	',2,',	2,	'thePO',	'edited',	'2017-12-12 23:52:40',	'',	'',	'0'),
(88,	'story',	11,	',2,',	2,	'thePO',	'edited',	'2017-12-12 23:53:04',	'',	'',	'0'),
(89,	'project',	2,	',2,',	2,	'thePO',	'started',	'2017-12-12 23:54:13',	'',	'',	'0'),
(90,	'user',	2,	',0,',	0,	'thePO',	'logout',	'2017-12-12 23:55:42',	'',	'',	'0'),
(91,	'user',	7,	',0,',	0,	'pm1',	'login',	'2017-12-12 23:55:49',	'',	'',	'0'),
(92,	'testtask',	1,	',2,',	2,	'pm1',	'opened',	'2017-12-12 23:56:59',	'',	'',	'0'),
(93,	'user',	7,	',0,',	0,	'pm1',	'logout',	'2017-12-12 23:57:22',	'',	'',	'0'),
(94,	'user',	11,	',0,',	0,	'pm1',	'login',	'2017-12-12 23:57:33',	'',	'',	'0'),
(95,	'case',	1,	',2,',	0,	'pm1',	'opened',	'2017-12-13 00:00:29',	'',	'',	'0'),
(96,	'case',	2,	',2,',	0,	'pm1',	'opened',	'2017-12-13 00:04:09',	'',	'',	'0'),
(97,	'testsuite',	1,	',0,',	0,	'pm1',	'opened',	'2017-12-13 00:05:13',	'',	'',	'0'),
(98,	'bug',	3,	',2,',	2,	'pm1',	'opened',	'2017-12-13 00:09:48',	'',	'',	'0'),
(99,	'case',	3,	',2,',	0,	'pm1',	'opened',	'2017-12-13 00:13:25',	'',	'',	'0'),
(100,	'case',	2,	',2,',	0,	'pm1',	'edited',	'2017-12-13 00:18:17',	'',	'',	'0'),
(101,	'bug',	4,	',2,',	2,	'pm1',	'opened',	'2017-12-13 00:30:07',	'',	'',	'0'),
(102,	'bug',	4,	',2,',	2,	'pm1',	'edited',	'2017-12-13 00:30:41',	'',	'',	'0'),
(103,	'bug',	3,	',2,',	2,	'pm1',	'edited',	'2017-12-13 00:31:10',	'',	'',	'0'),
(104,	'bug',	5,	',2,',	2,	'pm1',	'opened',	'2017-12-13 00:32:08',	'',	'',	'0'),
(105,	'user',	11,	',0,',	0,	'pm1',	'logout',	'2017-12-13 00:34:34',	'',	'',	'0');

INSERT INTO `zt_bug` (`id`, `product`, `branch`, `module`, `project`, `plan`, `story`, `storyVersion`, `task`, `toTask`, `toStory`, `title`, `keywords`, `severity`, `pri`, `type`, `os`, `browser`, `hardware`, `found`, `steps`, `status`, `color`, `confirmed`, `activatedCount`, `activatedDate`, `mailto`, `openedBy`, `openedDate`, `openedBuild`, `assignedTo`, `assignedDate`, `deadline`, `resolvedBy`, `resolution`, `resolvedBuild`, `resolvedDate`, `closedBy`, `closedDate`, `duplicateBug`, `linkBug`, `case`, `caseVersion`, `result`, `testtask`, `lastEditedBy`, `lastEditedDate`, `deleted`) VALUES
(1,	1,	0,	1,	1,	0,	1,	1,	1,	0,	0,	'Switch desktops three time, then the order of desktops is different from that before.',	'',	3,	0,	'codeerror',	'all',	'',	'',	'',	'<p>[Steps]Switch desktops three times.</p>\r\n<p>[Results]The order of desktops is different from that was before.</p>\r\n<p>[Expectations]The order of desktops is the same as that was before.</p>',	'active',	'',	0,	0,	'0000-00-00 00:00:00',	',pm1',	'pm1',	'2017-12-12 15:27:55',	'1',	'pm1',	'2017-12-12 15:27:55',	'2017-12-16',	'',	'',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	0,	'',	0,	0,	0,	0,	'',	'0000-00-00 00:00:00',	'0'),
(2,	1,	0,	2,	1,	0,	0,	1,	0,	0,	0,	'Full text retrieval in Word files. If Chinese characters exist in the file, it cannot be retrieved.',	'',	3,	0,	'codeerror',	'',	'',	'',	'',	'<p>[Steps]Enter “禅道(ZenTao)” in a Word and serach “禅道”.</p>\r\n<p>[Results] “禅道” is not found.</p>\r\n<p>[Expectations] “禅道” is found.</p>',	'active',	'',	0,	0,	'0000-00-00 00:00:00',	'',	'pm1',	'2017-12-12 15:30:32',	'1',	'pm1',	'2017-12-12 15:30:32',	'0000-00-00',	'',	'',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	0,	'',	0,	0,	0,	0,	'',	'0000-00-00 00:00:00',	'0'),
(3,	2,	0,	8,	2,	0,	10,	1,	0,	0,	0,	'400 page is incomplete.',	'',	3,	0,	'codeerror',	'',	'',	'',	'',	'<p>[Steps]</p>\r\n1. Click “400 page”<br /><p>[Results]</p>\r\n1. Not all 400 page is displayed.<br /><p>[Expectations]</p>\r\n1. List all 400 error pages.<br />',	'active',	'',	0,	0,	'0000-00-00 00:00:00',	'',	'pm1',	'2017-12-13 00:09:48',	'2',	'',	'0000-00-00 00:00:00',	'0000-00-00',	'',	'',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	0,	'',	2,	1,	1,	1,	'pm1',	'2017-12-13 00:31:10',	'0'),
(4,	2,	0,	8,	2,	0,	10,	1,	0,	0,	0,	'Generate sitemap.',	'',	3,	0,	'codeerror',	'',	'',	'',	'',	'<p>[Steps]</p>\r\n1. Click “Generate sitemap”.<br /><p>[Results]</p>\r\n1. ‘Contact Us’ page is missing in sitemap.<br /><p>[Expectations]</p>\r\n1. Generate sitemap.<br />',	'active',	'',	0,	0,	'0000-00-00 00:00:00',	'',	'pm1',	'2017-12-13 00:30:07',	'2',	'',	'0000-00-00 00:00:00',	'0000-00-00',	'',	'',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	0,	'',	2,	2,	0,	0,	'pm1',	'2017-12-13 00:30:41',	'0'),
(5,	2,	0,	8,	2,	0,	10,	1,	0,	0,	0,	'Database error in adding blogroll.',	'',	3,	0,	'codeerror',	'',	'',	'',	'',	'<p>[Steps]</p>\r\n1. Add ”www.zentao.pm\"<br /><p>[Results]</p>\r\n1. It cannot be added, and database error prompts.<br /><p>[Expectations]</p>1. In database form ‘friend link’, add ”www.zentao.pm\"<br />',	'active',	'',	0,	0,	'0000-00-00 00:00:00',	'',	'pm1',	'2017-12-13 00:32:08',	'2',	'',	'0000-00-00 00:00:00',	'0000-00-00',	'',	'',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	0,	'',	2,	2,	0,	0,	'',	'0000-00-00 00:00:00',	'0');

INSERT INTO `zt_build` (`id`, `product`, `branch`, `project`, `name`, `scmPath`, `filePath`, `date`, `stories`, `bugs`, `builder`, `desc`, `deleted`) VALUES
(1,	1,	0,	1,	'win10 V1.0build1',	'',	'',	'2017-12-12',	'',	'',	'pm1',	'',	'0'),
(2,	2,	0,	2,	'blog v0.1',	'',	'',	'2017-12-12',	'',	'',	'pm1',	'build for inner test<br />',	'0');

INSERT INTO `zt_burn` (`project`, `date`, `estimate`, `left`, `consumed`) VALUES
(1,	'2017-12-12',	280,	184,	68);

INSERT INTO `zt_case` (`id`, `product`, `branch`, `lib`, `module`, `path`, `story`, `storyVersion`, `title`, `precondition`, `keywords`, `pri`, `type`, `stage`, `howRun`, `scriptedBy`, `scriptedDate`, `scriptStatus`, `scriptLocation`, `status`, `color`, `frequency`, `order`, `openedBy`, `openedDate`, `reviewedBy`, `reviewedDate`, `lastEditedBy`, `lastEditedDate`, `version`, `linkCase`, `fromBug`, `fromCaseID`, `deleted`, `lastRunner`, `lastRunDate`, `lastRunResult`) VALUES
(1,	2,	0,	0,	8,	0,	11,	1,	'Cache setup test',	'',	'',	3,	'feature',	',unittest,feature',	'',	'',	'0000-00-00',	'',	'',	'normal',	'',	'1',	0,	'pm1',	'2017-12-13 00:00:29',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	1,	'',	0,	0,	'0',	'pm1',	'2017-12-13 00:10:24',	'pass'),
(2,	2,	0,	0,	8,	0,	10,	1,	'SEO setup test',	'',	'',	2,	'feature',	',feature',	'',	'',	'0000-00-00',	'',	'',	'normal',	'',	'1',	0,	'pm1',	'2017-12-13 00:04:09',	'',	'0000-00-00',	'pm1',	'2017-12-13 00:18:17',	2,	'',	0,	0,	'0',	'pm1',	'2017-12-13 00:23:48',	'fail'),
(3,	2,	0,	0,	8,	0,	0,	1,	'general setup test',	'',	'',	3,	'feature',	'',	'',	'',	'0000-00-00',	'',	'',	'normal',	'',	'1',	0,	'pm1',	'2017-12-13 00:13:25',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	1,	'',	0,	0,	'0',	'',	'0000-00-00 00:00:00',	'');

INSERT INTO `zt_casestep` (`id`, `parent`, `case`, `version`, `type`, `desc`, `expect`) VALUES
(1,	0,	1,	1,	'step',	'Switch on cache.',	'Cache is on.'),
(2,	0,	1,	1,	'step',	'Switch off cache.',	'Cache is off.'),
(3,	0,	2,	1,	'step',	'Click “Generate sitemap”',	'Sitemap is generated.'),
(4,	0,	2,	1,	'step',	'Click “400 page”',	'List all 400 error pages'),
(5,	0,	2,	1,	'step',	'Set “Individual Blog” as global keyword.',	'Set “Individual Blog” as site keyword.'),
(6,	0,	3,	1,	'step',	'Set self introduction as “Self Intro ABC&quot;',	'Self introduction is set as “Self Intro ABC&quot;'),
(7,	0,	2,	2,	'step',	'Click “Generate sitemap”',	'Sitemap is generated.'),
(8,	0,	2,	2,	'step',	'Click “400 page”',	'List all 400 error pages'),
(9,	0,	2,	2,	'step',	'Set “Individual Blog” as global keyword.”',	'Set “Individual Blog” as site keyword.”'),
(10,	0,	2,	2,	'step',	'Add ”www.zentao.pm&quot;',	'In database form ‘friend link’, add ”www.zentao.pm&quot;');

INSERT INTO `zt_dept` (`id`, `name`, `parent`, `path`, `grade`, `order`, `position`, `function`, `manager`) VALUES
(1,	'Product Dept',	0,	',1,',	1,	10,	'',	'',	''),
(2,	'Dev Dept',	0,	',2,',	1,	20,	'',	'',	''),
(3,	'QA Dept',	0,	',3,',	1,	30,	'',	'',	''),
(4,	'Ops Dept',	0,	',4,',	1,	40,	'',	'',	''),
(5,	'Dev 1',	2,	',2,5,',	2,	10,	'',	'',	''),
(6,	'Dev 2',	2,	',2,6,',	2,	20,	'',	'',	'');

INSERT INTO `zt_doclib` (`id`, `type`, `product`, `project`, `name`, `acl`, `groups`, `users`, `main`, `order`, `deleted`) VALUES
(1, 'product', 1,	0,	'Product Main Library',	'default',	'',	'',	'1',	0,	'0'),
(2, 'project', 0,	1,	'Project Main Library',	'default',	'',	'',	'1',	0,	'0'),
(3, 'product', 2,	0,	'Product Main Library',	'default',	'',	'',	'1',	0,	'0'),
(4, 'project', 0,	2,	'Project Main Library',	'default',	'',	'',	'1',	0,	'0');

INSERT INTO `zt_history` (`id`, `action`, `field`, `old`, `new`, `diff`) VALUES
(1,	7,	'desc',	'Finish developing system drive program.',	'Add advanced features such as remote desktop.',	'001- <del>Finish developing system drive program.</del>\n001+ <ins>Add advanced features such as remote desktop.</ins>'),
(2,	8,	'desc',	'Finish developing general features.<br />',	'Finish developing general features for users.<br />',	'001- <del>Finish developing features that are often used.<br /></del>\n001+ <ins>Finish developing general features for users.<br /></ins>'),
(3,	27,	'realStarted',	'0000-00-00',	'2017-12-12',	''),
(4,	27,	'assignedTo',	'pm1',	'pm1',	''),
(5,	27,	'status',	'wait',	'doing',	''),
(6,	28,	'left',	'100',	'64',	''),
(7,	28,	'consumed',	'0',	'8',	''),
(8,	29,	'realStarted',	'0000-00-00',	'2017-12-12',	''),
(9,	29,	'assignedTo',	'pm1',	'pm1',	''),
(10,	29,	'status',	'wait',	'doing',	''),
(11,	30,	'realStarted',	'0000-00-00',	'2017-12-12',	''),
(12,	30,	'assignedTo',	'pm1',	'pm1',	''),
(13,	30,	'status',	'wait',	'doing',	''),
(14,	31,	'left',	'60',	'0',	''),
(15,	31,	'consumed',	'0',	'60',	''),
(16,	31,	'status',	'doing',	'done',	''),
(17,	46,	'status',	'wait',	'doing',	''),
(18,	74,	'realStarted',	'0000-00-00',	'2017-12-12',	''),
(19,	74,	'status',	'wait',	'doing',	''),
(20,	75,	'left',	'30',	'55',	''),
(21,	75,	'consumed',	'0',	'8',	''),
(22,	75,	'status',	'wait',	'doing',	''),
(23,	78,	'realStarted',	'0000-00-00',	'2017-12-12',	''),
(24,	78,	'status',	'wait',	'doing',	''),
(25,	79,	'realStarted',	'0000-00-00',	'2017-12-12',	''),
(26,	79,	'status',	'wait',	'doing',	''),
(27,	80,	'consumed',	'0',	'30',	''),
(28,	80,	'assignedTo',	'pm1',	'pm1',	''),
(29,	80,	'finishedDate',	'',	'2017-12-12 23:47:37',	''),
(30,	80,	'left',	'40',	'0',	''),
(31,	80,	'status',	'doing',	'done',	''),
(32,	80,	'finishedBy',	'',	'pm1',	''),
(33,	84,	'left',	'60',	'50',	''),
(34,	84,	'consumed',	'0',	'8',	''),
(35,	87,	'pri',	'3',	'2',	''),
(36,	88,	'pri',	'3',	'1',	''),
(37,	89,	'status',	'wait',	'doing',	''),
(38,	100,	'version',	'1',	'2',	''),
(39,	100,	'steps',	'Click “Generate sitemap” EXPECT: Sitemap is generated.\nClick “400 page” EXPECT: List all 400 error pages.\nSet “Individual Blog” as global keyword. EXPECT: Set “Individual Blog” as site keyword.\n',	'Click “Generate sitemap” EXPECT: Sitemap is generated.\nClick “400 page” EXPECT: List all 400 error pages.\nSet “Individual Blog” as global keyword. EXPECT: Set “Individual Blog” as site keyword.\nAdd ”www.zentao.pm&quot; EXPECT:In database form ‘friend link’, add ”www.zentao.pmww.zentao.pm&quot;\n',	'004+ <ins> Add ”www.zentao.pm&quot; EXPECT:In database form ‘friend link’, add ”www.zentao.pmww.zentao.pm&quot;</ins>'),
(40,	102,	'title',	'SEO setup test',	'Generate sitemap',	'001- <del>SEO setup test</del>\n001+ <ins>Generate sitemap</ins>'),
(41,	102,	'steps',	'<p>[Steps]</p>\r\n1. Click “Generate sitemap”<br /><p>[Results]</p>\r\n1. ‘Contact Us’ page is missing in sitemap.<br /><p>[Expectations]</p>1. Generate sitemap<br />',	'<p>[Steps]</p>\r\n1. Click “Generate sitemap”<br /><p>[Results]</p>\r\n1. ‘Contact Us’ page is missing in sitemap.<br /><p>[Expectations]</p>\r\n1. Generate sitemap<br />',	'003- <del>1. ‘Contact Us’ page is missing in sitemap.<br /><p>[Expectations]</p>1. Generate sitemap<br /></del>\n003+ <ins>1. ‘Contact Us’ page is missing in sitemap.<br /><p>[Expectations]</p></ins>\n004+ <ins>1. Generate sitemap<br /></ins>'),
(42,	102,	'resolvedDate',	'0000-00-00 00:00:00',	'',	''),
(43,	103,	'title',	'SEO setup test',	'400 page is incomplete.',	'001- <del>SEO setup test</del>\n001+ <ins>400 page is incomplete.</ins>'),
(44,	103,	'steps',	'<p>[Steps]</p>\r\n1. Click “400 page”<br /><p>[Results]</p>\r\n1. Not all 400 page is displayed.<br /><p>[Expectations]</p>1. List all 400 error pages.<br />',	'<p>[Steps]</p>\r\n1. Click “400 page”<br /><p>[Results]</p>\r\n1. Not all 400 page is displayed.<br /><p>[Expectations]</p>\r\n1. List all 400 error pages.<br />',	'003- <del>1. Not all 400 page is displayed.<br /><p>[Expectations]</p>1. List all 400 error pages.<br /></del>\n003+ <ins>1. Not all 400 page is displayed.<br /><p>[Expectations]</p></ins>\n004+ <ins>1. List all 400 error pages.<br /></ins>'),
(45,	103,	'resolvedDate',	'0000-00-00 00:00:00',	'',	'');

INSERT INTO `zt_module` (`id`, `root`, `branch`, `name`, `parent`, `path`, `grade`, `order`, `type`, `owner`, `short`, `deleted`) VALUES
(1,	1,	0,	'Desktop',	0,	',1,',	1,	10,	'story',	'',	'',	'0'),
(2,	1,	0,	'My Doc',	0,	',2,',	1,	20,	'story',	'',	'',	'0'),
(3,	1,	0,	'Control Panel',	0,	',3,',	1,	30,	'story',	'',	'',	'0'),
(4,	1,	0,	'Task Manager',	0,	',4,',	1,	40,	'story',	'',	'',	'0'),
(5,	1,	0,	'Other',	0,	',5,',	1,	50,	'story',	'',	'',	'0'),
(6,	2,	0,	'Frontend',	0,	',6,',	1,	10,	'story',	'',	'',	'0'),
(7,	2,	0,	'Backend',	0,	',7,',	1,	20,	'story',	'',	'',	'0'),
(8,	2,	0,	'Global setup',	7,	',7,8,',	2,	10,	'story',	'',	'',	'0'),
(9,	2,	0,	'UI setup',	7,	',7,9,',	2,	20,	'story',	'',	'',	'0'),
(10,	2,	0,	'Manage Articles',	7,	',7,10,',	2,	30,	'story',	'',	'',	'0');

INSERT INTO `zt_product` (`id`, `name`, `code`, `line`, `type`, `status`, `desc`, `PO`, `QD`, `RD`, `acl`, `whitelist`, `createdBy`, `createdDate`, `createdVersion`, `order`, `deleted`) VALUES
(1,	'windows 10',	'win10',	0,	'normal',	'normal',	'',	'thePO',	'pm1',	'pm1',	'open',	'',	'thePO',	'2017-12-12 14:44:55',	'9.6.3',	5,	'0'),
(2,	'Blog',	'blog',	0,	'normal',	'normal',	'A blogging system<br />',	'thePO',	'pm1',	'pm1',	'open',	'',	'admin',	'2017-12-12 23:28:29',	'9.6.3',	10,	'0');

INSERT INTO `zt_productplan` (`id`, `product`, `branch`, `title`, `desc`, `begin`, `end`, `deleted`) VALUES
(1,	1,	0,	'win10 v1.0',	'Finish developing general features for users.<br />',	'2017-12-30',	'2018-07-03',	'0'),
(2,	1,	0,	'win10 v2.0',	'Add advanced features such as remote desktop.',	'2018-07-04',	'2019-01-05',	'0'),
(3,	2,	0,	'blog v0.1',	'Start developing blog<br />',	'2017-12-30',	'2018-01-29',	'0'),
(4,	2,	0,	'blog v0.2',	'Add more setup options of the blog.<br />',	'2018-01-30',	'2018-04-01',	'0');

INSERT INTO `zt_project` (`id`, `isCat`, `catID`, `type`, `parent`, `name`, `code`, `begin`, `end`, `days`, `status`, `statge`, `pri`, `desc`, `openedBy`, `openedDate`, `openedVersion`, `closedBy`, `closedDate`, `canceledBy`, `canceledDate`, `PO`, `PM`, `QD`, `RD`, `team`, `acl`, `whitelist`, `order`, `deleted`) VALUES
(1,	'0',	0,	'sprint',	0,	' 1st sprint of win10',	'win10Sprt1',	'2017-12-12',	'2017-12-25',	10,	'doing',	'1',	'1',	'Finish developing desktop.',	'',	0,	'9.6.3',	'',	0,	'',	0,	'',	'',	'',	'',	' 1st sprint of win10',	'open',	'',	5,	'0'),
(2,	'0',	0,	'sprint',	0,	'1st sprint of blog',	'blog v0.1',	'2017-12-12',	'2017-12-25',	10,	'doing',	'1',	'1',	'',	'',	0,	'9.6.3',	'',	0,	'',	0,	'',	'',	'',	'',	'1st sprint of blog',	'open',	'',	10,	'0');

INSERT INTO `zt_projectproduct` (`project`, `product`, `branch`) VALUES
(1,	1,	0),
(2,	2,	0);

INSERT INTO `zt_projectstory` (`project`, `product`, `story`, `version`, `order`) VALUES
(1,	1,	3,	1,	1),
(1,	1,	2,	1,	2),
(1,	1,	1,	1,	3),
(2,	2,	14,	1,	1),
(2,	2,	13,	1,	2),
(2,	2,	12,	1,	3),
(2,	2,	11,	1,	4),
(2,	2,	10,	1,	5),
(2,	2,	9,	1,	6);

INSERT INTO `zt_story` (`id`, `product`, `branch`, `module`, `plan`, `source`, `sourceNote`, `fromBug`, `title`, `keywords`, `type`, `pri`, `estimate`, `status`, `color`, `stage`, `mailto`, `openedBy`, `openedDate`, `assignedTo`, `assignedDate`, `lastEditedBy`, `lastEditedDate`, `reviewedBy`, `reviewedDate`, `closedBy`, `closedDate`, `closedReason`, `toBug`, `childStories`, `linkStories`, `duplicateStory`, `version`, `deleted`) VALUES
(1,	1,	0,	1,	'1',	'',	'',	0,	'Offer up to 10 desktops to users',	'',	'',	3,	100,	'active',	'',	'developing',	NULL,	'thePO',	'2017-12-12 14:58:30',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	0,	'',	'',	0,	1,	'0'),
(2,	1,	0,	1,	'1',	'',	'',	0,	'Custom desktop wallpaper.',	'',	'',	3,	80,	'active',	'',	'developing',	NULL,	'thePO',	'2017-12-12 14:58:30',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	0,	'',	'',	0,	1,	'0'),
(3,	1,	0,	1,	'1',	'',	'',	0,	'Auto classify files.',	'',	'',	3,	60,	'active',	'',	'projected',	NULL,	'thePO',	'2017-12-12 14:58:30',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	0,	'',	'',	0,	1,	'0'),
(4,	1,	0,	2,	'1',	'',	'',	0,	'Fast search document.',	'',	'',	3,	60,	'active',	'',	'planned',	NULL,	'thePO',	'2017-12-12 14:58:30',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	0,	'',	'',	0,	1,	'0'),
(5,	1,	0,	2,	'1',	'',	'',	0,	'Full text retrieval',	'',	'',	3,	50,	'active',	'',	'planned',	NULL,	'thePO',	'2017-12-12 14:58:30',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	0,	'',	'',	0,	1,	'0'),
(6,	1,	0,	2,	'1',	'',	'',	0,	'Add file preview.',	'',	'',	3,	60,	'active',	'',	'planned',	NULL,	'thePO',	'2017-12-12 14:58:30',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	0,	'',	'',	0,	1,	'0'),
(7,	1,	0,	4,	'1',	'',	'',	0,	'Process list',	'',	'',	3,	50,	'active',	'',	'planned',	NULL,	'thePO',	'2017-12-12 14:58:30',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	0,	'',	'',	0,	1,	'0'),
(8,	1,	0,	4,	'1',	'',	'',	0,	'Performance overview',	'',	'',	3,	60,	'active',	'',	'planned',	NULL,	'thePO',	'2017-12-12 14:58:30',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	0,	'',	'',	0,	1,	'0'),
(9,	2,	0,	8,	'3',	'',	'',	0,	'General setup',	'',	'',	3,	40,	'active',	'',	'developed',	NULL,	'thePO',	'2017-12-12 23:40:19',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	0,	'',	'',	0,	1,	'0'),
(10,	2,	0,	8,	'3',	'',	'',	0,	'SEO setup',	'',	'',	2,	50,	'active',	'',	'projected',	'',	'thePO',	'2017-12-12 23:40:19',	'',	'0000-00-00 00:00:00',	'thePO',	'2017-12-12 23:52:40',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	0,	'',	'',	0,	1,	'0'),
(11,	2,	0,	8,	'3',	'',	'',	0,	'cache setup',	'',	'',	1,	30,	'active',	'',	'developing',	'',	'thePO',	'2017-12-12 23:40:19',	'',	'0000-00-00 00:00:00',	'thePO',	'2017-12-12 23:53:04',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	0,	'',	'',	0,	1,	'0'),
(12,	2,	0,	8,	'3',	'',	'',	0,	'security setup',	'',	'',	3,	30,	'active',	'',	'developing',	NULL,	'thePO',	'2017-12-12 23:40:19',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	0,	'',	'',	0,	1,	'0'),
(13,	2,	0,	9,	'3',	'',	'',	0,	'UI setup',	'',	'',	3,	60,	'active',	'',	'developing',	NULL,	'thePO',	'2017-12-12 23:40:19',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	0,	'',	'',	0,	1,	'0'),
(14,	2,	0,	6,	'3',	'',	'',	0,	'Page display',	'',	'',	3,	60,	'active',	'',	'projected',	NULL,	'thePO',	'2017-12-12 23:40:19',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	0,	'',	'',	0,	1,	'0'),
(15,	2,	0,	10,	'4',	'',	'',	0,	'Article management',	'',	'',	3,	50,	'active',	'',	'planned',	NULL,	'thePO',	'2017-12-12 23:40:19',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	0,	'',	'',	0,	1,	'0');

INSERT INTO `zt_storyspec` (`story`, `version`, `title`, `spec`, `verify`) VALUES
(1,	1,	'Offer up to 10 desktops to users',	'User can do different tasks on different desktops.',	''),
(2,	1,	'Custom desktop wallpaper',	'',	''),
(3,	1,	'Auto classify files.',	'',	''),
(4,	1,	'Fast search document.',	'',	''),
(5,	1,	'Full text retrieval',	'',	''),
(6,	1,	'Add file preview.',	'',	''),
(7,	1,	'Process list',	'',	''),
(8,	1,	'Performance overview',	'',	''),
(9,	1,	'General setup',	'',	''),
(10,	1,	'SEO setup',	'Set SEO switch and report.',	''),
(11,	1,	'cache setup',	'Set cache switch and time.',	''),
(12,	1,	'security setup',	'',	''),
(13,	1,	'UI setup',	'',	''),
(14,	1,	'Page display',	'',	''),
(15,	1,	'Article management',	'',	'');

INSERT INTO `zt_suitecase` (`suite`, `product`, `case`, `version`) VALUES
(1,	0,	2,	1),
(1,	0,	1,	1);

INSERT INTO `zt_task` (`id`, `parent`, `project`, `module`, `story`, `storyVersion`, `fromBug`, `name`, `type`, `pri`, `estimate`, `consumed`, `left`, `deadline`, `status`, `color`, `mailto`, `desc`, `openedBy`, `openedDate`, `assignedTo`, `assignedDate`, `estStarted`, `realStarted`, `finishedBy`, `finishedDate`, `canceledBy`, `canceledDate`, `closedBy`, `closedDate`, `closedReason`, `lastEditedBy`, `lastEditedDate`, `deleted`) VALUES
(1,	0,	1,	1,	1,	1,	0,	'Offer up to 10 desktops to users.',	'devel',	3,	100,	8,	64,	'0000-00-00',	'doing',	'',	NULL,	'User can do different tasks on different desktops.',	'pm1',	'2017-12-12 15:05:06',	'pm1',	'2017-12-12 15:12:17',	'0000-00-00',	'2017-12-12',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'pm1',	'2017-12-12 15:13:13',	'0'),
(2,	0,	1,	1,	2,	1,	0,	'Custom desktop wallpaper',	'devel',	3,	80,	0,	80,	'0000-00-00',	'doing',	'',	NULL,	'',	'pm1',	'2017-12-12 15:05:06',	'pm1',	'2017-12-12 15:13:25',	'0000-00-00',	'2017-12-12',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'pm1',	'2017-12-12 15:13:25',	'0'),
(3,	0,	1,	1,	3,	1,	0,	'Auto classify files.',	'devel',	3,	60,	60,	0,	'0000-00-00',	'done',	'',	NULL,	'',	'pm1',	'2017-12-12 15:05:06',	'pm1',	'2017-12-12 15:14:47',	'0000-00-00',	'2017-12-12',	'pm1',	'2017-12-12 15:14:47',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'pm1',	'2017-12-12 15:14:47',	'0'),
(4,	0,	1,	1,	3,	1,	0,	'File type configuration',	'devel',	4,	40,	0,	40,	'0000-00-00',	'wait',	'',	NULL,	'',	'pm1',	'2017-12-12 15:05:06',	'pm1',	'2017-12-12 15:05:06',	'0000-00-00',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'',	'0000-00-00 00:00:00',	'0'),
(5,	0,	2,	8,	9,	1,	0,	'General backend setup',	'devel',	3,	40,	30,	0,	'0000-00-00',	'done',	'',	NULL,	'',	'pm1',	'2017-12-12 23:45:14',	'pm1',	'2017-12-12 23:47:37',	'0000-00-00',	'2017-12-12',	'pm1',	'2017-12-12 23:47:37',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'pm1',	'2017-12-12 23:47:37',	'0'),
(6,	0,	2,	8,	10,	1,	0,	'backend SEO setup',	'devel',	3,	50,	0,	50,	'0000-00-00',	'wait',	'',	NULL,	'Set SEO switch and report.',	'pm1',	'2017-12-12 23:45:14',	'pm1',	'2017-12-12 23:45:14',	'0000-00-00',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'',	'0000-00-00 00:00:00',	'0'),
(7,	0,	2,	8,	11,	1,	0,	'backend cache setup',	'devel',	3,	30,	8,	55,	'0000-00-00',	'doing',	'',	NULL,	'Set cache switch and time.',	'pm1',	'2017-12-12 23:45:14',	'pm1',	'2017-12-12 23:45:53',	'0000-00-00',	'2017-12-12',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'pm1',	'2017-12-12 23:45:53',	'0'),
(8,	0,	2,	8,	12,	1,	0,	'backend security setup',	'devel',	3,	30,	0,	30,	'0000-00-00',	'doing',	'',	NULL,	'',	'pm1',	'2017-12-12 23:45:14',	'pm1',	'2017-12-12 23:45:14',	'0000-00-00',	'2017-12-12',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'pm1',	'2017-12-12 23:45:35',	'0'),
(9,	0,	2,	9,	13,	1,	0,	'backend UI setup',	'devel',	3,	60,	8,	50,	'0000-00-00',	'doing',	'',	NULL,	'',	'pm1',	'2017-12-12 23:45:14',	'pm1',	'2017-12-12 23:45:14',	'0000-00-00',	'2017-12-12',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'pm1',	'2017-12-12 23:51:14',	'0'),
(10,	0,	2,	6,	14,	1,	0,	'Page display',	'devel',	3,	60,	0,	60,	'0000-00-00',	'wait',	'',	NULL,	'',	'pm1',	'2017-12-12 23:45:14',	'pm1',	'2017-12-12 23:45:14',	'0000-00-00',	'0000-00-00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	'',	'',	'0000-00-00 00:00:00',	'0');

INSERT INTO `zt_taskestimate` (`id`, `task`, `date`, `left`, `consumed`, `account`, `work`) VALUES
(1,	1,	'2017-12-12',	100,	0,	'pm1',	NULL),
(2,	1,	'2017-12-12',	64,	8,	'pm1',	'Finish UI design'),
(3,	2,	'2017-12-12',	80,	0,	'pm1',	NULL),
(4,	3,	'2017-12-12',	60,	0,	'pm1',	NULL),
(5,	3,	'2017-12-12',	0,	60,	'pm1',	'Finish'),
(6,	8,	'2017-12-12',	30,	0,	'pm1',	NULL),
(7,	7,	'2017-12-12',	55,	8,	'pm1',	''),
(8,	9,	'2017-12-12',	60,	0,	'pm1',	NULL),
(9,	5,	'2017-12-12',	40,	0,	'pm1',	NULL),
(10,	5,	'2017-12-12',	0,	30,	'pm1',	NULL),
(11,	9,	'2017-12-13',	50,	8,	'pm1',	'Finish page design');

INSERT INTO `zt_team` (`root`, `type`, `account`, `role`, `limited`, `join`, `days`, `hours`, `estimate`, `consumed`, `left`, `order`) VALUES
(2,	'project',	'thePO',	'Product Owner',	'no',	'2017-12-12',	10,	7.0,	0.00,	0.00,	0.00,	0),
(2,	'project',	'pm1',	'Project Manager',	'no',	'2017-12-12',	10,	7.0,	0.00,	0.00,	0.00,	0),
(1,	'project',	'thePO',	'Product Owner',	'no',	'2017-12-12',	10,	7.0,	0.00,	0.00,	0.00,	0),
(1,	'project',	'pm1',	'Project Manager',	'no',	'2017-12-12',	10,	7.0,	0.00,	0.00,	0.00,	0);

INSERT INTO `zt_testresult` (`id`, `run`, `case`, `version`, `caseResult`, `stepResults`, `lastRunner`, `date`) VALUES
(1,	1,	2,	1,	'fail',	'a:3:{i:3;a:2:{s:6:\"result\";s:4:\"pass\";s:4:\"real\";s:0:\"\";}i:4;a:2:{s:6:\"result\";s:4:\"fail\";s:4:\"real\";s:24:\"Not all 400 page is displayed.\";}i:5;a:2:{s:6:\"result\";s:4:\"pass\";s:4:\"real\";s:0:\"\";}}',	'pm1',	'2017-12-13 00:08:52'),
(2,	2,	1,	1,	'pass',	'a:2:{i:1;a:2:{s:6:\"result\";s:4:\"pass\";s:4:\"real\";s:0:\"\";}i:2;a:2:{s:6:\"result\";s:4:\"pass\";s:4:\"real\";s:0:\"\";}}',	'pm1',	'2017-12-13 00:10:24'),
(3,	0,	2,	2,	'fail',	'a:4:{i:7;a:2:{s:6:\"result\";s:4:\"fail\";s:4:\"real\";s:42:\"‘Contact Us’ page is missing in sitemap.\";}i:8;a:2:{s:6:\"result\";s:4:\"fail\";s:4:\"real\";s:36:\"400 page is missing.‘/notExitPage’；\";}i:9;a:2:{s:6:\"result\";s:4:\"pass\";s:4:\"real\";s:0:\"\";}i:10;a:2:{s:6:\"result\";s:3:\"n/a\";s:4:\"real\";s:45:\"Blogroll cannot be added, and database error prompts.\";}}',	'pm1',	'2017-12-13 00:23:41'),
(4,	0,	2,	2,	'fail',	'a:4:{i:7;a:2:{s:6:\"result\";s:4:\"fail\";s:4:\"real\";s:42:\"‘Contact Us’ page is missing in sitemap.\";}i:8;a:2:{s:6:\"result\";s:4:\"fail\";s:4:\"real\";s:36:\"400 page is missing.‘/notExitPage’；\";}i:9;a:2:{s:6:\"result\";s:4:\"pass\";s:4:\"real\";s:0:\"\";}i:10;a:2:{s:6:\"result\";s:4:\"fail\";s:4:\"real\";s:45:\"Blogroll cannot be added, and database error prompts.\";}}',	'pm1',	'2017-12-13 00:23:48');

INSERT INTO `zt_testrun` (`id`, `task`, `case`, `version`, `assignedTo`, `lastRunner`, `lastRunDate`, `lastRunResult`, `status`) VALUES
(1,	1,	2,	2,	'pm1',	'pm1',	'2017-12-13 00:08:52',	'fail',	'done'),
(2,	1,	1,	1,	'pm1',	'pm1',	'2017-12-13 00:10:24',	'pass',	'done');

INSERT INTO `zt_testsuite` (`id`, `product`, `name`, `desc`, `type`, `addedBy`, `addedDate`, `lastEditedBy`, `lastEditedDate`, `deleted`) VALUES
(1,	2,	'Testsuite',	'Testsuite',	'public',	'pm1',	'2017-12-13 00:05:13',	'',	'0000-00-00 00:00:00',	'0');

INSERT INTO `zt_testtask` (`id`, `name`, `product`, `project`, `build`, `owner`, `pri`, `begin`, `end`, `mailto`, `desc`, `report`, `status`, `deleted`) VALUES
(1,	'test for blog v0.1',	2,	2,	'2',	'thePO',	0,	'2018-01-05',	'2018-01-20',	'',	'',	'',	'wait',	'0');

INSERT INTO `zt_user` (`dept`, `account`, `password`, `role`, `realname`, `nickname`, `commiter`, `avatar`, `birthday`, `gender`, `email`, `skype`, `qq`, `weixin`, `dingding`, `slack`, `mobile`, `phone`, `address`, `zipcode`, `join`, `visits`, `ip`, `last`, `fails`, `locked`, `ranzhi`, `score`, `scoreLevel`, `deleted`) VALUES
(1,	'thePO',	'e10adc3949ba59abbe56e057f20f883e',	'po',	'Richard',	'',	'',	'',	'0000-00-00',	'm',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'0000-00-00',	3,	'127.0.0.1',	1513093950,	0,	'0000-00-00 00:00:00',	'',	0,	0,	'0'),
(5,	'pm1',	'e10adc3949ba59abbe56e057f20f883e',	'pm',	'Daniel',	'',	'',	'',	'0000-00-00',	'm',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'0000-00-00',	2,	'127.0.0.1',	1513093318,	0,	'0000-00-00 00:00:00',	'',	0,	0,	'0');

INSERT INTO `zt_usergroup` (`account`, `group`) VALUES
('pm1',	4),
('thePO',	5);
