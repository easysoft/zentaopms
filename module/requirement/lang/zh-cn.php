<?php
global $app;
$app->loadLang('story');
$lang->requirement = clone $lang->story;
$lang->requirement->common = $lang->URCommon;
