<?php
$filter->default->get['display'] = 'code';

$filter->entry = new stdclass();
$filter->entry->visit = new stdclass();
$filter->entry->visit->get['referer'] = 'reg::any';

$config->owt = new stdclass();
