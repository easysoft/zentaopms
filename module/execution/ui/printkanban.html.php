<?php
declare(strict_types=1);
/**
 * The close view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;
if($_POST) die(include './preview.html.php');

modalHeader
(
    set::title($lang->printKanban->common),
);

formPanel
(
    set::submitBtnText($lang->printKanban->print),
    set::target('_blank'),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->printKanban->content),
        radioList
        (
            set::name('content'),
            set::value('all'),
            set::inline(true),
            set::items($lang->printKanban->typeList)
        )
    ),
);
