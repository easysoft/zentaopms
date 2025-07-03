<?php
declare(strict_types=1);
/**
 * The metric library view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */
namespace zin;

$sqls = [];
foreach($config->admin->metricLib->updateSQLs as $key => $sql)
{
    $sqls[] = div
    (
        setID('sql' . $key),
        setClass('hidden'),
        icon(setClass('icon icon-spinner-indicator mr-2 animate-spin')),
        $sql
    );
}

panel
(
    setClass('m-auto w-2/3'),
    set::title($lang->metriclib->common),
    set::headingClass('justify-start border-b'),
    div
    (
        setClass('mb-4'),
        $lang->admin->metricLib->tips
    ),
    div
    (
        setID('updateSQLs'),
        setClass('mb-4'),
        $sqls,
        div
        (
            setID('updated'),
            setClass('text-success hidden'),
            icon(setClass('icon icon-check mr-2')),
            $lang->admin->metricLib->updated
        )
    ),
    a
    (
        setID('startUpdate'),
        setClass('btn primary'),
        on::click('updateMetriclib(1)'),
        $lang->admin->metricLib->startUpdate
    )
);
