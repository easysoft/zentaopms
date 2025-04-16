<?php
$config->deliverable = new stdclass();
$config->deliverable->create = new stdclass();
$config->deliverable->edit   = new stdclass();
$config->deliverable->create->requiredFields = 'name,module,method,model';
$config->deliverable->edit->requiredFields   = 'name,module,method,model';
