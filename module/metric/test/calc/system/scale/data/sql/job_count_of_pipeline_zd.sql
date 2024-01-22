INSERT INTO zt_job(`id`, `name`, `repo`, `product`, `frame`, `engine`, `server`, `pipeline`, `triggerType`, `sonarqubeServer`, `projectKey`, `svnDir`, `atDay`, `atTime`, `customParam`, `comment`, `createdBy`, `createdDate`, `editedBy`, `lastStatus`, `lastTag`, `deleted`)
  VALUES ('1', '这是一个Job1', '1', '1', 'sonarqube', 'jenkins', '3', 'dave', 'tag', '2', 'zentaopms', '/module/caselib', '0', '22', '[]', 'a', 'admin', '24/01/19', '', 'a', 'a', '0'), 
         ('2', '这是一个Job2', '2', '2', 'junit', 'gitlab', '1', '{"project":"1569"', 'commit', '0', 'zentaopms', '/module/effort', '1', '22', '[]', 'b', 'admin', '24/01/19', '', 'b', 'b', '0'), 
         ('3', '这是一个Job3', '3', '3', 'testng', 'jenkins', '3', '"reference":"master"}', 'schedule', '0', 'zentaopms', '/module/git', '2', '22', '[]', 'c', 'admin', '24/01/19', '', 'c', 'c', '0'), 
         ('4', '这是一个Job4', '4', '4', 'phpunit', 'gitlab', '1', 'dave', 'tag', '0', 'zentaopms', '/module/mail', '3', '22', '[]', 'd', 'admin', '24/01/19', '', 'd', 'd', '0'), 
         ('5', '这是一个Job5', '5', '5', 'pytest', 'jenkins', '3', '{"project":"1569"', 'commit', '0', 'zentaopms', '/module/output', '4', '22', '[]', 'e', 'admin', '24/01/19', '', 'e', 'e', '0'), 
         ('6', '这是一个Job6', '6', '6', 'jtest', 'gitlab', '1', '"reference":"master"}', 'schedule', '0', 'zentaopms', '/module/caselib', '5', '22', '[]', 'f', 'admin', '24/01/19', '', 'f', 'f', '0'), 
         ('7', '这是一个Job7', '7', '7', 'cppunit', 'jenkins', '3', 'dave', 'tag', '0', 'zentaopms', '/module/effort', '6', '22', '[]', 'g', 'admin', '24/01/19', '', 'g', 'g', '0'), 
         ('8', '这是一个Job8', '8', '8', 'gtest', 'gitlab', '1', '{"project":"1569"', 'commit', '0', 'zentaopms', '/module/git', '0', '22', '[]', 'h', 'admin', '24/01/19', '', 'h', 'h', '0'), 
         ('9', '这是一个Job9', '9', '9', 'qtest', 'jenkins', '3', '"reference":"master"}', 'schedule', '0', 'zentaopms', '/module/mail', '1', '22', '[]', 'i', 'admin', '24/01/19', '', 'i', 'i', '0'), 
         ('10', '这是一个Job10', '10', '10', 'sonarqube', 'gitlab', '1', 'dave', 'tag', '2', 'zentaopms', '/module/output', '2', '22', '[]', 'j', 'admin', '24/01/19', '', 'j', 'j', '0'); 
