<?php
declare(strict_types=1);
/**
 * The export view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;
include '../../file/ui/export.html.php';

$canBatchEdit         = common::hasPriv('task', 'batchEdit', !empty($execution) ? $execution : null);
$canBatchClose        = common::hasPriv('task', 'batchClose', !empty($execution) ? $execution : null) && strtolower($type) != 'closed';
$canBatchCancel       = common::hasPriv('task', 'batchCancel', !empty($execution) ? $execution : null) && strtolower($type) != 'cancel';
$canBatchAssignTo     = common::hasPriv('task', 'batchAssignTo', !empty($execution) ? $execution : null);
$canBatchChangeModule = common::hasPriv('task', 'batchChangeModule', !empty($execution) ? $execution : null);
$canBatchAction       = in_array(true, array($canBatchEdit, $canBatchClose, $canBatchCancel, $canBatchChangeModule, $canBatchAssignTo));

if(!$canBatchAction)
{
    global $lang;
    query('#exportPanel .form-row.exportRange')->replaceWith
    (
        formRow
        (
            formGroup
            (
                set::label($lang->file->exportRange),
                set::control('picker'),
                set::name('exportType'),
                set::items($lang->exportTypeList),
                set::required(true),
                set::disabled(true)
            )
        )
    );
}
