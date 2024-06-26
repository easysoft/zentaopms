<?php
declare(strict_types=1);
/**
 * The edit view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/inputinject.html.php');

jsVar('titleNotEmpty', sprintf($lang->error->notempty, $lang->doc->title));
jsVar('requiredFields', ',' . $config->doc->create->requiredFields . ',');
jsVar('libNotEmpty', sprintf($lang->error->notempty, $lang->doc->lib));
jsVar('keywordsNotEmpty', sprintf($lang->error->notempty, $lang->doc->keywords));
jsVar('contentNotEmpty', sprintf($lang->error->notempty, $lang->doc->content));

$doc->type == 'text' ? form
(
    set::actions(''),
    set::ajax(array('beforeSubmit' => jsRaw("clickSubmit"))),
    setID('docEditForm'),
    div
    (
        setClass('flex titleBox'),
        backBtn(setClass('btn secondary'), set::icon('back'), $lang->goback),
        formGroup
        (
            set::id('titleBox'),
            input
            (
                set::name('title'),
                set::value($doc->title),
                set::maxlength(100),
                set::placeholder($lang->doc->titlePlaceholder)
            )
        ),
        $doc->status == 'draft' ? btn
        (
            set
            (
                array
                (
                    'class' => 'btn secondary mr-2 save-draft',
                    'text'  => $lang->doc->saveDraft,
                    'btnType' => 'submit'
                )
            )
        ) : null,
        btn
        (
            set
            (
                array
                (
                    'class' => 'btn primary save-btn',
                    'text'  => $lang->doc->release,
                    'btnType' => 'submit'
                )
            )
        ),
        btn
        (
            set
            (
                array
                (
                    'class'       => 'btn ghost',
                    'icon'        => 'cog-outline',
                    'text'        => $lang->settings,
                    'data-toggle' => 'modal',
                    'id'          => 'basicInfoLink',
                    'url'         => '#modalBasicInfo'
                )
            )
        )
    ),
    editor
    (
        set::name('content'),
        set::size('full'),
        set::resizable(false),
        $doc->contentType == 'markdown' ? set::markdown(true) : null,
        html($doc->content)
    ),
    formHidden('status', $doc->status),
    formHidden('contentType', $doc->contentType),
    formHidden('type', 'text'),
    modalTrigger
    (
        modal
        (
            modalHeader(set::title($lang->doc->basicInfo), set::entityText(''), set::entityID(0)),
            set::id('modalBasicInfo'),
            on::change('#product',   "loadObjectModules"),
            on::change('#project',   "loadObjectModules"),
            on::change('#execution', "loadObjectModules"),
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
                set::label($lang->doc->files),
                fileSelector()
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
                        setClass('btn primary'),
                        set::btnType('button'),
                        setData('dismiss', 'modal'),
                        $lang->save
                    )
                )
            )
        )
    )
) : form
(
    modalHeader(),
    on::change('#product',   "loadObjectModules"),
    on::change('#project',   "loadObjectModules"),
    on::change('#execution', "loadObjectModules"),
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
        set::label($lang->doc->title),
        set::name('title'),
        set::value($doc->title),
        set::required(true)
    ),
    formGroup
    (
        strpos($config->doc->officeTypes, $doc->type) === false ? setClass('hidden') : null,
        set::label($lang->doc->keywords),
        set::control('input'),
        set::name('keywords'),
        set::value($doc->keywords)
    ),
    formGroup
    (
        set::label($lang->doc->files),
        fileSelector()
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
    formGroup
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
    ),
    formHidden('contentType', $doc->contentType),
    formHidden('type', $doc->type),
    formHidden('status', $doc->status),
    formHidden('parent', $doc->parent)
);
