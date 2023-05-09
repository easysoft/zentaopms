<?php
declare(strict_types=1);
/**
* The project block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Wangyuting <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$isChinese = in_array($this->app->getClientLang(), array('zh-cn','zh-tw'));

foreach($projects as $project)
{
    $programBudget = $isChinese ? round((float)$project->budget / 10000, 2) . $this->lang->project->tenThousand : round((float)$project->budget, 2);

    $project->PM        = zget($users, $project->PM, $project->PM);
    $project->status    = zget($lang->project->statusList, $project->status);
    $project->consumed .= $lang->execution->workHourUnit;
    $project->budget    = $project->budget != 0 ? zget($lang->project->currencySymbol, $project->budgetUnit) . ' ' . $programBudget : $lang->project->future;
}

dtable
(
    set::width('100%'),
    set::height('auto'),
    set::cols(array_values($config->block->dtable->project->fieldList)),
    set::data(array_values($projects))
);

render();
