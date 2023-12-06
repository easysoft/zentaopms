<?php
declare(strict_types=1);
/**
 * The communicate file of stakeholder module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wangy Yidong <yidong@cnezsoft.com>
 * @package     stakeholder
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::entityID($user->id), set::entityText($user->name));
formPanel
(
    formGroup
    (
        set::label($lang->stakeholder->communicate),
        set::name('comment'),
        set::control('editor')
    )
);
hr();
history();
