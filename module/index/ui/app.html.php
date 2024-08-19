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
        .tutorial-hl {position: relative; z-index: 1000; box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);}
        CSS)
    );
}

render('pagebase');
