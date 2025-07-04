ALTER TABLE `zt_workflowgroup` CHANGE `projectModel` `projectModel` varchar(30) NOT NULL DEFAULT '';
INSERT INTO `zt_workflowgroup` (`type`, `projectModel`, `projectType`, `name`, `code`, `status`, `vision`, `main`) VALUES
('project',	'agileplus',	  'product',	'融合敏捷式产品研发',	'agileplusproduct',	    'normal',	'rnd',	'1'),
('project',	'agileplus',	  'project',	'融合敏捷式项目研发',	'agileplusproject',	    'normal',	'rnd',	'1'),
('project',	'waterfallplus',  'product',	'融合瀑布式产品研发',	'waterfallplusproduct',	'normal',	'rnd',	'1'),
('project',	'waterfallplus',  'project',	'融合瀑布式项目研发',	'waterfallplusproject',	'normal',	'rnd',	'1'),
('project',	'kanban',	      'product',	'看板式产品研发',	    'kanbanproduct',	    'normal',	'rnd',	'1'),
('project',	'kanban',	      'project',	'看板式项目研发',	    'kanbanproject',	    'normal',	'rnd',	'1'),
('project',	'ipd',	          'ipd',	    'IPD集成产品研发',	    'ipdproduct',	        'normal',	'rnd',	'1'),
('project',	'ipd',	          'tpd',	    'IPD预研产品研发',	    'tpdproduct',	        'normal',	'rnd',	'1'),
('project',	'ipd',	          'cbb',	    'IPD平台产品研发',	    'cbbproduct',	        'normal',	'rnd',	'1'),
('project',	'ipd',	          'cpdproduct',	'IPD定制产品研发',	    'cpdproduct',	        'normal',	'rnd',	'1'),
('project',	'ipd',	          'cpdproject',	'IPD定制项目研发',	    'cpdproject',	        'normal',	'rnd',	'1');

ALTER TABLE `zt_bug`
CHANGE `injection` `injection` varchar(30) NOT NULL DEFAULT '',
CHANGE `identify` `identify` varchar(30) NOT NULL DEFAULT '';
