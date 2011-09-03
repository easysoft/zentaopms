<?php
$config->task->exportFields = '
    id, project, story,
    name, desc,
    type, pri,  deadline, status,estimate, consumed, left,
    mailto,
    openedBy, openedDate, assignedTo, assignedDate, 
    finishedBy, finishedDate, canceledBy, canceledDate,
    closedBy, closedDate, closedReason,
    lastEditedBy, lastEditedDate,files
    ';
