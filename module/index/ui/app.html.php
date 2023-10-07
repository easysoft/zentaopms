<?php
namespace zin;

set::zui(true);
set::class('page-app');
jsVar('window.defaultAppUrl', $defaultUrl);

render('pagebase');
