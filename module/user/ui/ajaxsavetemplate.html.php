<?php
declare(strict_types=1);
/**
 * The ajax save template view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($title));

formPanel
(
    setID('saveTemplate'),
    formGroup
    (
        set::label($lang->usertpl->title),
        set::name('title'),
        set::required(true)
    ),
    hasPriv('user', 'setPublicTemplate') ? formGroup
    (
        set::label(''),
        set::width('1/1'),
        checkbox
        (
            set::name('public'),
            set::value(1),
            set::text($lang->user->setPublicTemplate)
        )
    ) : null,
    formHidden('editor', $editor),
    formHidden('type', $type),
    formHidden('content')
);
