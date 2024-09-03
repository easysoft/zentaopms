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
        .tutorial-hl {position: relative; z-index: 1000!important; opacity: 1!important;}
        .tutorial-popover .arrow::before {border: 3px solid var(--color-primary-500); width: 10px; height: 10px; border-radius: 50%}
        .tutorial-popover .arrow-top::before {top: calc(-34px - var(--arrow-size))}
        .tutorial-popover .arrow-left::before {left: calc(-34px - var(--arrow-size))}
        .tutorial-popover .arrow-right::before {right: calc(-34px - var(--arrow-size))}
        .tutorial-popover .arrow-bottom::before {bottom: calc(-34px - var(--arrow-size))}
        .tutorial-popover .arrow::after {content: ' '; position: absolute; background: var(--color-primary-500); display: block; width: 32px; height: 32px; visibility: visible;}
        .tutorial-popover .arrow-top::after {width: 2px; top: calc(var(--arrow-size) - 34px); left: 4px}
        .tutorial-popover .arrow-left::after {height: 2px; left: calc(var(--arrow-size) - 34px); top: 4px}
        .tutorial-popover .arrow-right::after {height: 2px; right: calc(var(--arrow-size) - 34px); top: 4px}
        .tutorial-popover .arrow-bottom::after {width: 2px; bottom: calc(var(--arrow-size) - 34px); left: 4px}
        CSS)
    );
}

render('pagebase');
