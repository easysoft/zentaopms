<?php
declare(strict_types=1);

namespace zin;

panel
(
    setID('tipsModal'),
    set::title($lang->space->tips),
    set::headingActions(array
    (
        array('url' => createLink('space', 'view', "spaceID={$spaceID}"), 'icon' => 'close', 'class' => 'ghost')
    )),
    setClass('m-auto'),
    div
    (
        set::className('flex items-center mt-2'),
        icon('check-circle text-success icon-2x mr-2'),
        span
        (
            set::className('text-md font-bold tip-title'),
            $lang->space->afterInfo
        )
    ),
    div
    (
        setClass('mt-5 mb-5'),
        hasPriv('space', 'manageMembers') ? btn
        (
            set::className('mr-2 tipBtn ml-1'),
            $lang->space->setMember,
            set::target('_blank'),
            set::url(createLink('space', 'manageMembers', "spaceID={$spaceID}"))
        ) : null,
        hasPriv('space', 'group') ? btn
        (
            set::className('mr-2 tipBtn'),
            $lang->space->setACL,
            set::target('_blank'),
            set::url(createLink('space', 'group', "spaceID={$spaceID}"))
        ) : null,
        btn
        (
            set::className('tipBtn'),
            $lang->space->goback,
            set::target('_blank'),
            set::url(createLink('space', 'browse'))
        )
    )
);

/* ====== Render page ====== */
render();
