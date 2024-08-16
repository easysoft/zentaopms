<?php
declare(strict_types=1);
/**
 * The createSpace view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang<yidong@chandao.com>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->doc->createSpace),
    set::actions(false),
    formGroup
    (
        set::label($lang->doclib->spaceName),
        set::width('5/6'),
        inputGroup
        (
            input(set::name('name')),
            btn(setClass('primary'), set::btnType('submit'), $lang->save),
        )
    )
);
