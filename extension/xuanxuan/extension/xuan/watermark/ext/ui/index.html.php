<?php
/**
 * The admin view file of watermark module of XXB.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd., www.zentao.net)
 * @license     ZOSL (https://zpl.pub/page/zoslv1.html)
 * @package     watermark
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
                on::change()->call('window.watermarkIndex.toggleWatermarkTr'),
                set::name('enabled'),
                set::items($lang->watermark->switchList),
                set::value($enabled),
                set::inline(true)
            )
        ),
        formGroup(
            setID('watermark-tr'),
            set::label($lang->watermark->content),
            textarea(
                set::name('content'),
                set::value($content)
            )
        ),
        formGroup(
            setID('watermark-tip-tr'),
            set::label(''),
            div(
                p(
                    icon(
                        set::name('exclamation-sign'),
                        setClass('text-warning mr-1 mb-2')
                    ),
                    $lang->watermark->varTip
                ),
                tableData(
                    item(
                        set::name('displayName:'),
                        $lang->watermark->displayName
                    ),
                    item(
                        set::name('account:'),
                        $lang->watermark->account
                    ),
                    item(
                        set::name('email:'),
                        $lang->watermark->email
                    ),
                    item(
                        set::name('phone:'),
                        $lang->watermark->phone
                    ),
                    item(
                        set::name('date:'),
                        $lang->watermark->date
                    )
                )
            )
        )
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
            $enabled == 1
                ? item(
                    set::name($lang->watermark->content),
                    html("<pre>$content</pre>")
                )
                : null,
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
