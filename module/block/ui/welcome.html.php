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
            set('class', 'col col-left'),
            h4
            (
                set('class', 'user-welcome'),
                sprintf($lang->block->welcomeList[$welcomeType], $app->user->realname)
            ),
            avatar
            (
                set::text($app->user->realname),
                set::src($app->user->avatar)
            )
        ),
        div
        (
            set('class', 'col col-center'),
            h4('待我评审：'),
            div
            (
                set('class', 'row tiles'),
                div
                (
                    set('class', 'col tile'),
                    div
                    (
                        set('class', 'tile-amount text-primary'),
                        '123',
                    ),
                    div
                    (
                        set('class', 'tile-title'),
                        '反馈数:'
                    ),
                    !empty($delay['task'])
                        ? div
                        (
                            set('class', 'tile-info'),
                            span
                            (
                                set('class', 'label label-danger label-outline welcome-label'),
                                $lang->block->delayed . ' ' . $delay['task']
                            )
                        )
                        : null
                ),
                div
                (
                    set('class', 'col tile'),
                    div
                    (
                        set('class', 'tile-amount text-primary'),
                        333,
                    ),
                    div
                    (
                        set('class', 'tile-title'),
                        '用例数'
                    ),
                    div
                    (
                        set('class', 'tile-info'),
                        span
                        (
                            set('class', 'label label-danger label-outline welcome-label'),
                            $lang->block->delayed . ' ' . $delay['task']
                        )
                    )
                ),
                div
                (
                    set('class', 'col tile'),
                    div
                    (
                        set('class', 'tile-amount text-primary'),
                        444,
                    ),
                    div
                    (
                        set('class', 'tile-title'),
                        '基线数'
                    ),

                    div
                    (
                        set('class', 'tile-info'),
                        span
                        (
                            set('class', 'label label-danger label-outline welcome-label'),
                            $lang->block->delayed . ' ' . $delay['task']
                        )
                    )
                )
            )
        ), 
        div
        (
            set('class', 'col col-right'),
            h4('指派给我：'),
            div
            (
                set('class', 'row tiles'),
                div
                (
                    set('class', 'col tile'),
                    div
                    (
                        set('class', 'tile-amount text-primary'),
                        1313,
                    ),
                    div
                    (
                        set('class', 'tile-title'),
                        $lang->block->myTask
                    ),
                    div
                    (
                        set('class', 'tile-info'),
                        span
                        (
                            set('class', 'label label-danger label-outline welcome-label'),
                            $lang->block->delayed . ' ' . $delay['task']
                        )
                    )
                ),
                div
                (
                    set('class', 'col tile'),
                    div
                    (
                        set('class', 'tile-amount text-primary'),
                        3333,
                    ),
                    div
                    (
                        set('class', 'tile-title'),
                        $lang->block->myBug
                    ),
                    div
                    (
                        set('class', 'tile-info'),
                        span
                        (
                            set('class', 'label label-danger label-outline welcome-label'),
                            $lang->block->delayed . ' ' . $delay['task']
                        )
                    )
                )
            )
        ) 
    )
);
render();
