<?php
$config->actionsMap = new stdclass();

/* Normal. */
$config->actionsMap->icon     = new stdclass();
$config->actionsMap->icon->start     = 'icon-start';
$config->actionsMap->icon->suspend   = 'icon-pause';
$config->actionsMap->icon->close     = 'icon-off';
$config->actionsMap->icon->activate  = 'icon-magic';
$config->actionsMap->icon->edit      = 'icon-edit';
$config->actionsMap->icon->create    = 'icon-split';
$config->actionsMap->icon->delete    = 'icon-trash';
$config->actionsMap->icon->team      = 'icon-group';
$config->actionsMap->icon->group     = 'icon-lock';
$config->actionsMap->icon->link      = 'icon-link';
$config->actionsMap->icon->whitelist = 'icon-shield-check';
$config->actionsMap->icon->delete    = 'icon-trash';

/* Other. */
$config->actionsMap->other = new stdclass();
$config->actionsMap->other->type  = 'dropdown';
$config->actionsMap->other->caret = true;

$config->actionsMap->other->dropdown = new stdclass();
$config->actionsMap->other->dropdown->placement = 'bottom-end';

/* More. */
$config->actionsMap->more = new stdclass();
$config->actionsMap->more->type  = 'dropdown';
$config->actionsMap->more->icon  = 'icon-ellipsis-v';
$config->actionsMap->more->caret = false;

$config->actionsMap->more->dropdown = new stdclass();
$config->actionsMap->more->dropdown->placement = 'bottom-end';
