<?php
declare(strict_types=1);
/**
 * The change view file of story module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     story
 * @link        https://www.zentao.net
 */
namespace zin;

$formItems = array();
$formItems['reviewer'] = formGroup
(
    set::width('full'),
    set::label($fields['reviewer']['title']),
    inputGroup
    (
        select
        (
            set::name('reviewer[]'),
            set::multiple(true),
            set::items($fields['reviewer']['options']),
            set::default($fields['reviewer']['default']),
        ),
        $forceReview ? null : span
        (
            setClass('input-group-addon'),
            checkbox
            (
                set::id('needNotReview'),
                set::name('needNotReview'),
                set::checked($needReview),
                set::text($lang->story->needNotReview)
            )
        )
    ),
    set::required($fields['reviewer']['required'])
);
$formItems['title'] = formGroup
(
    set::width('full'),
    set::label($fields['title']['title']),
    inputGroup
    (
        input
        (
            set::name('title'),
            set::default($fields['title']['default']),
        ),
        span
        (
            setClass('input-group-addon'),
            input
            (
                set::name('color'),
                set::type('color'),
                set::default($fields['color']['default']),
            )
        ),
        empty($story->twins) ? null : span
        (
            setClass('input-group-addon'),
            checkbox
            (
                set::id('relievedTwins'),
                set::name('relievedTwins'),
                set::title($lang->story->changeRelievedTwinsTips),
            )
        )
    ),
    set::required($fields['title']['required'])
);
$formItems['status'] = formRow
(
    set::hidden(true),
    formGroup
    (
        input(set::name('status'), set::default($fields['status']['default']))
    )
);
$formItems['spec'] = formGroup
(
    set::label($fields['spec']['title']),
    set::control($fields['spec']['control']),
    set::default($fields['spec']['default']),
    set::tip($lang->story->specTemplate)
);
unset($fields['reviewer'], $fields['title'], $fields['color'], $fields['status'], $fields['spec']);

foreach($fields as $field => $attr)
{
    $fieldName = zget($attr, 'name', $field);
    $control   = array();
    $control['type'] = $attr['control'];
    if(!empty($attr['options'])) $control['items'] = $attr['options'];

    $formItems[$field] = formGroup
    (
        set::width('full'),
        set::name($fieldName),
        set::label($attr['title']),
        set::control($control),
        set::value($attr['default']),
        set::required($attr['required'])
    );
}
$formItems['file'] = formGroup
(
    set::width('full'),
    set::label($lang->attatch),
    input
    (
        set::type('file'),
        set::name('files'),
    )
);
$formItems['affected'] = formGroup
(
    set::width('full'),
    set::label($lang->attatch),
    tabs
(
    tabPane
    (
        set::key('legend-basic'),
        set::title('基本信息'),
        set::active(true),
        tableData
        (
            item
            (
                set::name('所属执行'),
                '企业网站第一期'
            ),
            item
            (
                set::name('指派给'),
                '开发丙'
            ),
            item
            (
                set::name('任务类型'),
                '开发'
            ),
            item
            (
                set::name('任务状态'),
                '已完成'
            ),
            item
            (
                set::name('进度'),
                '100 %'
            ),
            item
            (
                set::name('优先级'),
                priLabel(1)
            ),
        )
    ),
    tabPane
    (
        set::key('legend-life'),
        set::title('任务的一生'),
        tableData
        (
            item
            (
                set::name('由谁创建'),
                'admin 于 2021-04-28 13:16:15'
            ),
            item
            (
                set::name('由谁完成'),
                '开发丙 于 2021-04-06 15:30:00'
            ),
            item
            (
                set::name('由谁取消'),
                '暂无'
            ),
            item
            (
                set::name('由谁关闭'),
                '暂无'
            ),
            item
            (
                set::name('关闭原因'),
                '暂无'
            ),
            item
            (
                set::name('最后编辑'),
                '开发丙 于 2021-04-28 13:21:51'
            ),
        )
    )
)
);

formPanel
(
    set::title(''),
    div
    (
        span
        (
            setClass('form-label'),
            $lang->story->changed
        ),
        div
        (
            setClass('form-group title'),
            $story->title,
            span
            (
                setClass('label text-gray size-sm'),
                $story->id
            )
        ),
    ),
    $formItems
);
render();
