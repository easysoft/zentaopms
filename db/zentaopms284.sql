update `zt_task` set `assignedTo` = '' where `mode` = 'multi' and `status` != 'done' and `status` != 'closed';
