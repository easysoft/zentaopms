<?php
declare(strict_types=1);
/**
 * The safe view file of extension module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     extension
 * @link        https://www.zentao.net
 */
namespace zin;

panel
(
    p
    (
        setClass('text-danger mb-3'),
        html($error)
    ),
    btn
    (
        on::click(isInModal() ? "loadModal('', '', {loadingClass: ''})" : "loadCurrentPage"),
        set::type('primary'),
        $lang->extension->refreshPage
    )
);

render();
