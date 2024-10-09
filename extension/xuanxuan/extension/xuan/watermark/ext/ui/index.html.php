<?php
/**
 * The admin view file of conference module of XXB.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd., www.zentao.net)
 * @license     ZOSL (https://zpl.pub/page/zoslv1.html)
 * @package     conference
 * @version     $Id$
 * @link        https://xuanim.com
 */
namespace zin;

if($type == 'edit')
{
    formPanel(
        set::title($lang->watermark->common),
        set::labelWidth('140px'),
        formGroup(
            set::label($lang->watermark->switch),
            radioList(
                set::name('enabled'),
                set::items($lang->watermark->switchList),
                set::value($enabled),
                set::inline(true),
            ),
        ),
    );
}
else
{
    panel(
        set::title($lang->watermark->common),
        set::size('sm'),
        tableData(
            item(
                set::name($lang->watermark->switch),
                $lang->watermark->switchList[$enabled]
            ),
            item(
                a(
                    setClass('btn primary'),
                    set::href(inLink('index', 'type=edit')),
                    $lang->edit
                )
            )
        )
    );
}

render();
