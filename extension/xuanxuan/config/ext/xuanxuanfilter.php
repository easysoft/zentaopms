<?php
$filter->default->get['display'] = 'code';

$filter->entry = new stdclass();
$filter->entry->visit = new stdclass();
$filter->entry->visit->get['referer'] = 'reg::any';

$filter->im = new stdclass();
$filter->im->authorize = new stdclass();
$filter->im->authorize->paramValue['url'] = 'reg::any';

$config->owt = new stdclass();
