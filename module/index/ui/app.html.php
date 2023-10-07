<?php
namespace zin;

set::zui(true);
set::className('page-app');
jsVar('window.defaultAppUrl', $defaultUrl);

render('pagebase');
