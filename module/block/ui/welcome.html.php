<?php
namespace zin;

div
(
    set('class', 'panel-body conatiner-fluid'),
    div
    (
        set('class', 'table-row'),
        div
        (
            set('class', 'col col-left hide-in-sm'),
            h4(date(DT_DATE3)),
            h4
            (
                set('class', 'user-welcome'),
                printf($lang->block->welcomeList[$welcomeType], $app->user->realname)
            )
        )
    )
);
render();
