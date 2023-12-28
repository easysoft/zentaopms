<?php
declare(strict_types=1);
/**
 * The create view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

if($docType != '' and strpos($config->doc->officeTypes, $docType) !== false)
{
    set::title($lang->doc->create);
    if($this->config->edition != 'open')
    {
        div
        (
            setClass('alert warning-pale bd bd-warning'),
            html
            (
                sprintf($lang->doc->notSetOffice, zget($lang->doc->typeList, $docType),
                common::hasPriv('custom', 'libreoffice') ? $this->createLink('custom', 'libreoffice', '', '', true) : '###')
            )
        );
    }
    else
    {
        div
        (
            setClass('alert warning-pale bd bd-warning'),
            html(sprintf($lang->doc->cannotCreateOffice, zget($lang->doc->typeList, $docType)))
        );
    }
}
else
{
    jsVar('titleNotEmpty', sprintf($lang->error->notempty, $lang->doc->title));
    jsVar('requiredFields', ',' . $config->doc->create->requiredFields . ',');
    jsVar('libNotEmpty', sprintf($lang->error->notempty, $lang->doc->lib));
    jsVar('keywordsNotEmpty', sprintf($lang->error->notempty, $lang->doc->keywords));
    jsVar('contentNotEmpty', sprintf($lang->error->notempty, $lang->doc->content));

    $projectRow = null;
    if($linkType == 'project')
    {
        $projectRow = formRow
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
                    on::change('loadExecutions')
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
        );
    }

    form
    (
        set::actions(''),
        set::ajax(array('beforeSubmit' => jsRaw("clickSubmit"))),
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
                    set::placeholder($lang->doc->titlePlaceholder)
                )
            ),
            btn
            (
                set
                (
                    array
                    (
                        'class' => 'btn secondary save-draft mr-2',
                        'text'  => $lang->doc->saveDraft,
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
                        'id'          => 'basicInfoLink',
                        'class'       => 'btn primary',
                        'text'        => $lang->doc->release,
                        'data-toggle' => 'modal',
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
            set::placeholder($lang->noticePasteImg)
        ),
        formHidden('status', 'normal'),
        formHidden('contentType', 'html'),
        formHidden('type', 'text'),
        modalTrigger
        (
            modal
            (
                set::title($lang->doc->release . $lang->doc->common),
                set::id('modalBasicInfo'),
                on::change('#product',   "loadObjectModules"),
                on::change('#project',   "loadObjectModules"),
                on::change('#execution', "loadObjectModules"),
                $projectRow,
                ($linkType == 'execution') ? formGroup
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
                ($linkType == 'product') ? formGroup
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
                    set::label($lang->doc->lib),
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
                    set::label($lang->doc->files),
                    upload()
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
                    picker
                    (
                        set::name('groups[]'),
                        set::items($groups),
                        set::multiple(true)
                    ),
                    picker
                    (
                        set::name('users[]'),
                        set::items($users),
                        set::multiple(true)
                    )
                ),
                formRow
                (
                    div
                    (
                        setClass('form-actions form-group no-label'),
                        btn
                        (
                            set
                            (
                                array
                                (
                                    'class'   => 'btn primary',
                                    'btnType' => 'submit'
                                )
                            ),
                            $lang->doc->release
                        )
                    )
                )
            )
        )
    );
}
