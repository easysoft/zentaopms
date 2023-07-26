<?php
declare(strict_types=1);
/**
 * The table engine view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('refresh', $lang->refresh);
jsVar('changingTable', $lang->admin->changingTable);
jsVar('changeFinished', $lang->admin->changeFinished);
jsVar('allInnoDB', $lang->admin->engineSummary['allInnoDB']);
jsVar('hasMyISAM', $lang->admin->engineSummary['hasMyISAM']);

$MyISAMCount = 0;
$engineList  = array();
foreach($tableEngines as $tableName => $engine)
{
    if($engine != 'InnoDB') $MyISAMCount++;
    $engineList[] = div
    (
        setClass('flex items-center my-1 pl-5 h-5'),
        set(array('data-table' => $tableName)),
        div
        (
            setClass('rounded-full black mr-2 w-1 h-1')
        ),
        html(sprintf($lang->admin->engineInfo, $tableName, $engine))
    );
}

$title = $MyISAMCount > 0 ? sprintf($lang->admin->engineSummary['hasMyISAM'], $MyISAMCount) : $lang->admin->engineSummary['allInnoDB'];

panel
(
    set::title($title),
    set::titleClass('table-engine'),
    set::headingClass('justify-start border-b'),
    $MyISAMCount > 0 ? to::headingActions
    (
        button
        (
            on::click('changeAllEngines'),
            setClass('btn primary'),
            $lang->admin->changeEngine
        )
    ) : null,
    div
    (
        setID('engineBox'),
        $engineList
    )
);

render();
