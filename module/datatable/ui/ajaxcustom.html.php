<?php
declare(strict_types=1);
/**
 * The ajaxcustom view file of datatable module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

setClass('edit-cols');
set::title($lang->datatable->custom);
set::titleClass('flex-none');
to::header(span($lang->datatable->customTip, setClass('text-gray', 'text-md')));
set::footerClass('justify-center');

jsVar('ajaxSaveUrl', $this->createLink('datatable', 'ajaxSave', "module={$module}&method={$method}"));

toolbar
(
    btn
    (
        set::text($lang->save),
        set::type('primary'),
        set::class('w-28'),
        on::click('saveCustomCols')
    )
);

render('modalDialog');
