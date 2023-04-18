<?php
namespace zin;

set::zui(true);
jsVar('window.defaultAppUrl', empty($defaultUrl) ? '${DEFAULT_URL}' : $defaultUrl);

render('pagebase');
