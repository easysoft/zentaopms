<?php
declare(strict_types=1);
/**
 * The start view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jinyong Zhu<zhujinyong@easycorp.ltd>
 * @package     tree
 * @link        https://www.zentao.net
 */
namespace zin;
/* ====== Preparing and processing page data ====== */
jsVar('confirmFinish', $lang->task->confirmFinish);
jsVar('noticeTaskStart', $lang->task->noticeTaskStart);
jsVar('confirmRoot4Doc', $lang->tree->confirmRoot4Doc);
jsVar('confirmRoot', $lang->tree->confirmRoot);
jsVar('moduleID', $module->id);
jsVar('moduleRoot', $module->root);
jsVar('moduleParent', $module->parent);
jsVar('type', $type);

/* zin: Set variables to define control for form. */
$hidden = $type != 'story' && $module->type == 'story';

/* ====== Define the page structure with zin widgets ====== */
modalHeader(set::title($title));
formPanel
(
    setID('editForm'),
    set::action(helper::createLink($app->rawModule, $app->rawMethod, 'module=' . $module->id .'&type=' . $type)),
    set::submitBtnText($lang->save),
    $showProduct ? formRow
    (
        set::className($hiddenProduct ? 'hidden' : ''),
        formGroup
        (
            set::label($lang->tree->product),
            picker
            (
                set::name('root'),
                set::value($module->root),
                set::items($products),
                on::change('loadBranches')
            )
        ),
        $product->type != 'normal' ? formGroup
        (
            setClass('branchBox'),
            control
            (
                set::type('picker'),
                set::name('branch'),
                set::value($module->branch),
                set::items($branches),
                on::change('loadModules')
            )
        ) : null
    ) : null,
    $type == 'doc' ? formGroup
    (
        set::label($lang->doc->lib),
        picker
        (
            set::name('root'),
            set::value($module->root),
            set::items($libs),
            set::required(true),
            on::change('changeRoot')
        )
    ) : null,
    formGroup
    (
        set::name('name'),
        set::label($name),
        set::control('input'),
        set::value($module->name)
    ),
    $module->type != 'line' ? formGroup
    (
        set::className($hidden ? 'moduleBox hidden' : 'moduleBox'),
        set::label(($type == 'doc' or $type == 'api') ? $lang->tree->parentCate : $lang->tree->parent),
        picker
        (
            set::name('parent'),
            set::value($module->parent),
            set::items($optionMenu),
            set::required(true)
        )
    ) : null,
    $type == 'bug' ? formGroup
    (
        set::label($lang->tree->owner),
        control
        (
            set::type('picker'),
            set::name('owner'),
            set::value($module->owner),
            set::items($users)
        )
    ) : null,
    formGroup
    (
        set::label($lang->tree->short),
        inputControl
        (
            input
            (
                set::name('short'),
                set::value($module->short)
            )
        )
    )
);
