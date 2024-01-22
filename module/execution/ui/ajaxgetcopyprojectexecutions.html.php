<?php
declare(strict_types=1);
/**
 * The ajaxGetCopyProjectExecutions view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('hasExecution', !empty($executions));

$executionsBox = array();
if(empty($executions))
{
    $executionsBox[] = div
        (
            setClass('inline-flex items-center'),
            setClass('alert with-icon w-full'),
            icon('exclamation-sign text-gray icon-2x pl-2 text-warning'),
            span
            (
                set::className('font-bold ml-2'),
                $lang->execution->copyNoExecution
            )
        );
}
else
{
    foreach($executions as $id => $execution)
    {
        if(empty($execution->multiple)) continue;

        $executionsBox[] = btn(
            setClass('execution-block justify-start'),
            setClass($copyExecutionID == $id ? 'primary-outline' : ''),
            set('data-id', $id),
            icon
            (
                setClass('text-gray'),
                $lang->icons[$execution->type]
            ),
            span($execution->name)
        );
    }
}

/* ====== Render page ====== */
render();
