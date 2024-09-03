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
if(!common::isTutorialMode()) $headingActions['items'][] = array('type' => 'secondary', 'class' => 'save-draft', 'text' => $lang->doc->saveDraft, 'btnType' => 'submit');
$headingActions['items'][] = array('type' => 'primary', 'class' => 'btn-wide release-btn', 'text' => $lang->doc->release, 'url' => '#modalBasicInfo', 'data-toggle' => 'modal');

$basicInfoModal = modal
(
    set::title($lang->doc->release . $lang->doc->common),
    set::id('modalBasicInfo'),
    set::bodyClass('form form-horz'),
    on::change('[name=space],[name=product],[name=execution]')->call('loadObjectModules', jsRaw('event')),
    on::change('[name=lib]')->call('loadLibModules', jsRaw('event')),
    on::change('[name=project]')->call('loadExecutions', jsRaw('event')),
    on::change('[name=lib],[name^=users]', 'checkLibPriv'),
    $lib->type == 'project' ? formRow
    (
        formGroup
        (
            setClass('w-1/2'),
            set::label($lang->doc->project),
            set::required(true),
            set::control(array('control' => 'picker', 'name' => 'project', 'items' => $objects, 'required' => true, 'value' => isset($execution) ? $execution->project : $objectID))
        ),
        ($this->app->tab == 'doc' and $config->vision == 'rnd') ? formGroup
        (
            setClass('w-1/2'),
            set::label($lang->doc->execution),
            set::control(array('control' => 'picker', 'name' => 'execution', 'items' => $executions, 'value' => isset($execution) ? $objectID : ''))
        ) : null
    ) : null,
    ($lib->type == 'execution') ? formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->execution),
        set::required(true),
        set::control(array('control' => 'picker', 'name' => 'execution', 'items' => $objects, 'required' => true, 'value' => $objectID))
    ) : null,
    ($lib->type == 'product') ? formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->product),
        set::required(true),
        set::control(array('control' => 'picker', 'name' => 'product', 'items' => $objects, 'required' => true, 'value' => $objectID))
    ) : null,
    isset($spaces) ? formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->space),
        set::required(true),
        set::control(array('control' => 'picker', 'name' => 'space', 'items' => $spaces, 'required' => true, 'value' => $objectID, 'disabled' => $lib->type == 'mine'))
    ) : null,
    formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->lib),
        set::required(true),
        picker(set::name('lib'), set::items($libs), set::value(isset($libs[$libID]) ? $libID : ''), set::required(true))
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->module),
        picker(set::name('module'), set::items($optionMenu), set::value($moduleID), set::required(true))
    ),
    formGroup
    (
        setClass('flex items-center preserve-on-morph'),
        set::label($lang->doc->title),
        set::name('title'),
        set::control('input'),
        set::required(),
        on::change()->do('$("#showTitle").val($("#title").val())')
    ),
    formGroup
    (
        set::label($lang->doc->keywords),
        set::control('input'),
        set::name('keywords')
    ),
    formGroup
    (
        setStyle('min-height', 'auto'),
        set::label($lang->doc->files),
        fileSelector()
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
        setID('whiteListBox'),
        setClass('hidden'),
        set::label($lang->doc->whiteList),
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
            btn(set::type('primary'), set::btnType('submit'), setClass('saveBasicInfoBtn'), $lang->doc->release)
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
        $(e.target).removeClass('has-changed').find('input[name=status]').val(isDraft ? 'draft' : 'normal');
    }
    JS;

formBase
(
    setID('docForm'),
    set::actions(false),
    set::ajax(array('beforeSubmit' => jsRaw($handleSubmitForm), 'onFail' => jsRaw('() => $("#docForm").addClass("has-changed")'))),
    set::morph(),
    setData('unsavedConfirm', $lang->doc->confirmLeaveOnEdit),
    on::change('#showTitle,zen-editor')->once()->do('$element.addClass("has-changed")'),
    panel
    (
        setClass('doc-form preserve-on-morph'),
        to::heading
        (
            input
            (
                setClass('ring-0 text-xl font-bold px-0'),
                setID('showTitle'),
                set::maxlength(100),
                set::placeholder($lang->doc->titlePlaceholder),
                setData('title-hint', sprintf($lang->error->notempty, $lang->doc->title)),
                on::init()->do('$element.on("change input", () => {$("#title").val($element.val()).removeClass("has-error");$("#titleTip").remove();})')
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
