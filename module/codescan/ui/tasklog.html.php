<?php
declare(strict_types=1);
/**
 * The task log file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;
include 'task.header.html.php';

$methodName = 'ajaxGetTaskLog';
$logParams  = "repoID={$task->repo_id}&pipelineName=" . str_replace('-', '*', $task->pipeline_name) . "&executionID={$task->execution_number}";
$logID      = $taskID;
include  '../../ci/ext/ui/logs.html.php';
panel
(
    div
    (
        setID('taskMenu'),
        setClass('flex justify-between'),
        $headers
    ),
    $logBlock
);
