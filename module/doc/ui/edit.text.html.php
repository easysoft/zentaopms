<?php
declare(strict_types=1);
/**
 * The edit view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Hao<sunhao@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

include 'lefttree.html.php';

$headingActions = array('class' => 'gap-3 pr-1', 'items' => array());
if($doc->status == 'draft') $headingActions['items'][] = array('type' => 'secondary', 'class' => 'save-draft', 'text' => $lang->doc->saveDraft, 'btnType' => 'submit');
$headingActions['items'][] = array('type' => 'primary', 'class' => 'btn-wide', 'text' => $lang->doc->release, 'btnType' => 'submit');
$headingActions['items'][] = array('type' => 'ghost', 'icon' => 'cog-outline', 'text' => $lang->settings, 'url' => '#modalBasicInfo', 'data-toggle' => 'modal');

$basicInfoModal = modal
(
    set::id('modalBasicInfo'),
    set::bodyClass('form form-horz'),
    set::title($lang->doc->release . $lang->doc->common),
    bind::change('#product,#project,#execution', 'loadObjectModules'),
    $type == 'execution' ? formHidden('project', $doc->project) : null,
    (strpos('product|project|execution', $type) !== false) ? formGroup
    (
        set::width('1/2'),
        set::required(true),
        set::label($lang->doc->{$type}),
        picker
        (
            set::name($type),
            set::id($type),
            set::items($objects),
            set::value($objectID),
            set::required(true)
        )
    ) : null,
    formGroup
    (
        set::width('1/2'),
        set::required(true),
        set::label($lang->doc->libAndModule),
        picker
        (
            set::name('module'),
            set::items($moduleOptionMenu),
            set::value($doc->lib . '_' . $doc->module),
            set::required(true)
        )
    ),
    formGroup
    (
        (strpos($config->doc->officeTypes, $doc->type) === false && $doc->type != 'text') ? setClass('hidden') : null,
        set::label($lang->doc->keywords),
        set::control('input'),
        set::name('keywords'),
        set::value($doc->keywords)
    ),
    formGroup
    (
        set::label($lang->doc->mailto),
        mailto(set::items($users), set::value($doc->mailto))
    ),
    formGroup
    (
        set::label($lang->doclib->control),
        radioList
        (
            set::name('acl'),
            set::items($lang->doc->aclList),
            set::value($doc->acl),
            on::change('toggleWhiteList')
        )
    ),
    $lib->type != 'mine' ? formGroup
    (
        $doc->acl == 'open' ? setClass('hidden') : null,
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
                    set::multiple(true),
                    set::value($doc->groups)
                )
            ),
            div
            (
                setClass('w-full'),
                userPicker(set::label($lang->doc->users), set::items($users), set::value($doc->users))
            )
        )
    ) : null,
    formRow
    (
        div
        (
            setClass('form-actions form-group no-label'),
            btn
            (
                set::type('primary'),
                set::btnType('button'),
                setData('dismiss', 'modal'),
                $lang->save
            )
        )
    )
);

$handleSubmitForm = <<<'JS'
    function(e)
    {
        const isDraft = $(e.submitter).hasClass('save-draft');
        const $title = $('#title');
        if(isDraft && !$title.val().length)
        {
            zui.Modal.alert($title.data('titleHint')).then(() => $title[0].focus());
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
                set::name('title'),
                set::value($doc->title),
                set::maxlength(100),
                set::placeholder($lang->doc->titlePlaceholder),
                setData('title-hint', sprintf($lang->error->notempty, $lang->doc->title))
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
            set::markdown($doc->contentType == 'markdown'),
            html($doc->content)
        )
    ),
    formHidden('status', $doc->status),
    formHidden('contentType', $doc->contentType),
    formHidden('type', 'text'),
    $basicInfoModal
);
