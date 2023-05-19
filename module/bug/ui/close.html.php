<?php
declare(strict_types=1);
/**
 * The close view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

set::title($bug->title);
form
(
    set::actions(array('submit')),
    set::submitBtnText($lang->bug->close),
    set::class('pb-6 border-b'),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor')
    )
);
history();

render('modalDialog');
