<?php
declare(strict_types=1);
/**
 * The moveDoc view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@chandao.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;

modalHeader(set::title(empty($doc->parent) ? $lang->docTemplate->moveDocTemplate : $lang->docTemplate->moveSubTemplate), set::entityText($doc->title), set::entityID($docID));

$whiteListHidden = $doc->acl == 'private' ? '' : 'hidden';

unset($modules[0]);
formPanel
(
    on::change('[name=lib]')->call('loadScopeTypes', jsRaw("event")),
    empty($doc->parent) ? formGroup
    (
        set::label($lang->docTemplate->scope),
        set::required(true),
        picker(set::name('lib'), set::items($scopeItems), set::value($doc->lib), set::required(true))
    ) : null,
    empty($doc->parent) ? formGroup
    (
        set::label($lang->docTemplate->module),
        set::required(true),
        picker(set::name('module'), set::items($modules), set::value($doc->module), set::required(true))
    ) : null,
    !empty($doc->parent) ? formGroup
    (
        set::label($lang->docTemplate->parent),
        picker
        (
            set::name('parent'),
            set::items($chapterAndDocs),
            set::value($doc->parent),
            set::required(true)
        ),
    ) : null,
    empty($doc->parent) ? formGroup
    (
        set::label($lang->doclib->control),
        radioList
        (
            set::name('acl'),
            set::items($lang->doc->aclListA),
            set::value($doc->acl),
            on::change('toggleWhiteList')
        )
    ) : null,
    empty($doc->parent) ? formGroup
    (
        setID('whiteListBox'),
        setClass($whiteListHidden),
        set::label($lang->doc->whiteList),
        div
        (
            setClass('w-full check-list'),
            inputGroup
            (
                setClass('w-full'),
                $lang->doc->groupLabel,
                picker
                (
                    set::name('groups[]'),
                    set::items($groups),
                    set::value($doc->groups),
                    set::multiple(true)
                )
            ),
            div
            (
                setClass('w-full'),
                userPicker
                (
                    set::label($lang->doc->userLabel),
                    set::items($users),
                    set::value($doc->users)
                )
            )
        )
    ) : null
);
