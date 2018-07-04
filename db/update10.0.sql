ALTER TABLE `zt_storyspec` CHANGE `title` `title` varchar(255) NOT NULL;
update `zt_todo` set assignedTo = 'closed', assignedDate = closedDate where status = 'closed';
