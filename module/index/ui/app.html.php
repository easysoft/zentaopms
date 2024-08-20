<?php
namespace zin;

set::zui(true);
set::className('page-app');
jsVar('window.defaultAppUrl', $defaultUrl);

if(commonModel::isTutorialMode())
{
    to::head
    (
        h::css(<<<'CSS'
        .tutorial-hl {position: relative; z-index: 1000!important;}
        CSS)
    );
}

render('pagebase');
