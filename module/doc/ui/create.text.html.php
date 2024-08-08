<?php
declare(strict_types=1);
/**
 * The create view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Hao<sunhao@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

include 'lefttree.html.php';

$headingActions = array('class' => 'gap-3 pr-1', 'items' => array());
$headingActions['items'][] = array('type' => 'secondary', 'class' => 'save-draft', 'text' => $lang->doc->saveDraft, 'btnType' => 'submit');
$headingActions['items'][] = array('type' => 'primary', 'class' => 'btn-wide', 'text' => $lang->doc->release, 'url' => '#modalBasicInfo', 'data-toggle' => 'modal');

$basicInfoModal = modal
(
    set::title($lang->doc->release . $lang->doc->common),
    set::id('modalBasicInfo'),
    set::bodyClass('form form-horz'),
    on::change('#modalBasicInfo [name=product]',   "loadObjectModules"),
    on::change('#modalBasicInfo [name=project]',   "loadExecutions"),
    on::change('#modalBasicInfo [name=execution]', "loadObjectModules"),
    formGroup
    (
        setClass('flex items-center'),
        set::label($lang->doc->title),
        set::name('title'),
        set::control('input'),
        set::required(),
        on::change()->do('$("#showTitle").val($("#title").val())')
    ),
    $lib->type == 'project' ? formRow
    (
        formGroup
        (
            setClass('w-1/2'),
            set::label($lang->doc->project),
            picker
            (
                set::name('project'),
                set::id('project'),
                set::items($objects),
                isset($execution) ? set::value($execution->project) : set::value($objectID),
            )
        ),
        ($this->app->tab == 'doc' and $config->vision == 'rnd') ? formGroup
        (
            setClass('w-1/2'),
            set::label($lang->doc->execution),
            picker
            (
                set::name('execution'),
                set::id('execution'),
                set::items($executions),
                set::value(isset($execution) ? $objectID : '')
            )
        ) : null
    ) : null,
    ($lib->type == 'execution') ? formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->execution),
        set::required(true),
        picker
        (
            set::name('execution'),
            set::id('execution'),
            set::items($objects),
            set::value($objectID),
            set::required(true)
        )
    ) : null,
    ($lib->type == 'product') ? formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->product),
        set::required(true),
        picker
        (
            set::name('product'),
            set::id('product'),
            set::items($objects),
            set::value($objectID),
            set::required(true)
        )
    ) : null,
    formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->libAndModule),
        set::required(true),
        picker
        (
            set::name('module'),
            set::items($moduleOptionMenu),
            set::value($moduleID),
            set::required(true)
        )
    ),
    formGroup
    (
        set::label($lang->doc->keywords),
        set::control('input'),
        set::name('keywords')
    ),
    formGroup
    (
        set::label($lang->doc->mailto),
        mailto(set::items($users))
    ),
    formGroup
    (
        set::label($lang->doclib->control),
        radioList
        (
            set::name('acl'),
            set::items($lang->doc->aclList),
            set::value($objectType == 'mine' ? 'private' : 'open'),
            on::change('toggleWhiteList')
        )
    ),
    formGroup
    (
        setClass('hidden'),
        set::label($lang->doc->whiteList),
        set::id('whitelistBox'),
        div
        (
            setClass('w-full check-list'),
            inputGroup
            (
                setClass('w-full'),
                $lang->doc->groups,
                picker
                (
                    set::name('groups[]'),
                    set::items($groups),
                    set::multiple(true)
                )
            ),
            div
            (
                setClass('w-full'),
                userPicker(set::label($lang->doc->users), set::items($users))
            )
        )
    ),
    formRow
    (
        div
        (
            setClass('form-actions form-group no-label'),
            btn
            (
                set::type('primary'),
                set::btnType('submit'),
                $lang->doc->release
            )
        )
    )
);

$handleSubmitForm = <<<'JS'
    function(e)
    {
        const isDraft = $(e.submitter).hasClass('save-draft');
        const $showTitle = $('#showTitle');
        if(isDraft && !$showTitle.val().length)
        {
            zui.Modal.alert($showTitle.data('titleHint')).then(() => $showTitle[0].focus());
            return false;
        }
        $(e.target).find('input[name=status]').val(isDraft ? 'draft' : 'normal');
    }
    JS;

formBase
(
    set::actions(false),
    set::ajax(array('beforeSubmit' => jsRaw($handleSubmitForm))),
    panel
    (
        setClass('doc-form'),
        to::heading
        (
            input
            (
                setClass('ring-0 text-xl font-bold px-0'),
                setID('showTitle'),
                set::maxlength(100),
                set::placeholder($lang->doc->titlePlaceholder),
                setData('title-hint', sprintf($lang->error->notempty, $lang->doc->title)),
                on::init()->do('$element.on("change input", () => $("#title").val($element.val()))')
            )
        ),
        set::headingActions($headingActions),
        set::headingClass('py-3'),
        set::bodyClass('p-0 border-t'),
        editor
        (
            set::name('content'),
            set::size('full'),
            set::resizable(false),
            set::placeholder($lang->noticePasteImg)
        )
    ),
    formHidden('status', 'normal'),
    formHidden('contentType', 'html'),
    formHidden('type', 'text'),
    $basicInfoModal
);
