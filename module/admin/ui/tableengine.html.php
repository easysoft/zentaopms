<?php
declare(strict_types=1);
/**
 * The table engine view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */
namespace zin;

$isMysql = $config->db->driver == 'mysql';
$count   = count($tableEngines);

jsVar('refresh', $lang->refresh);
jsVar('tableEngines', $tableEngines);
jsVar('changingTable', $lang->admin->changingTable);
jsVar('changeSuccess', $lang->admin->changeSuccess);
jsVar('changeFinished', $lang->admin->changeFinished);
jsVar('tableEngineFail', $lang->admin->tableEngineFail);
jsVar('hasMyISAM', $lang->admin->engineSummary);

if(!$isMysql || empty($tableEngines))
{
    panel
    (
        setClass('m-auto w-2/3'),
        set::title($lang->admin->tableEngine),
        set::headingClass('border-b'),
        div
        (
            setClass('flex items-center text-success'),
            icon(setClass('mr-2'), 'check-circle'),
            $lang->admin->noNeedUpdate
        )
    );
}
else
{
    panel
    (
        setClass('m-auto w-2/3'),
        set::title($lang->admin->tableEngine),
        set::headingClass('border-b'),
        to::headingActions
        (
            span(setID('engineProgress'), '0 / ' . $count)
        ),
        div
        (
            setClass('mb-4'),
            sprintf($lang->admin->tableEngineTips, $count)
        ),
        div
        (
            setID('engineBox'),
            setClass('mb-4 overflow-y-auto overflow-x-hidden'),
            setStyle(['max-height' => 'calc(100vh - 16rem)'])
        ),
        div
        (
            setID('engineAction'),
            setClass('center'),
            a
            (
                setID('startUpdate'),
                setClass('btn primary'),
                on::click('changeTableEngines()'),
                $lang->admin->startUpdate
            )
        )
    );
}

render();
