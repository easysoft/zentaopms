<?php
namespace zin;

set::zui(true);
jsVar('defaultUrl', empty($defaultUrl) ? '${DEFAULT_URL}' : $defaultUrl);

render('pagebase');
