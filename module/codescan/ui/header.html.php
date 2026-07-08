<?php
declare(strict_types=1);
namespace zin;
global $app;

$headers = nav
(
    setClass('flex-auto'),
    li
    (
        setClass('nav-item link'),
        a
        (
            $lang->codescan->browse,
            set::href(inLink('rulesetview', "setID={$setID}&type=rule")),
            $type == 'rule' ? setClass('active') : null
        )
    ),
    li
    (
        setClass('nav-item'),
        a
        (
            $lang->codescan->viewRuleset,
            set::href(inLink('rulesetview', "setID={$setID}&type=view")),
            set('data-app', $app->tab),
            $type == 'view' ? setClass('active') : null
        )
    )
);
