<?php
declare(strict_types=1);
/**
 * The showxminimport view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;
panel
(
    set::title("{$lang->testcase->xmindImportEdit}({$product->name})"),
    set::headingClass('p-6'),
    set::bodyClass('px-6 py-0'),
    mindmap
    (
        set::data($scenes),
        set::height('600px')
    ),
    div
    (
        set::class('py-6 text-center'),
        btn(set::class('primary btn-wide'), $lang->save),
        backBtn(set::class('btn-wide ml-4'), $lang->goback)
    )
);
render();
