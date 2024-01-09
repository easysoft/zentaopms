<?php
declare(strict_types=1);
/**
 * The export view file of release module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     release
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->release->export),
    set::ajax(array('closeModal' => 'onlySuccess')),
    set::target('_self'),
    formGroup
    (
        set::label($lang->release->fileName),
        set::required(true),
        input(set::name('fileName'))
    ),
    formGroup
    (
        set::label($lang->release->exportRange),
        set::name('type'),
        set::value('all'),
        set::items($lang->release->exportTypeList),
        set::required(true)
    ),
    set::submitBtnText($lang->export),
    set::formClass('border-0')
);

/* ====== Render page ====== */
render();
