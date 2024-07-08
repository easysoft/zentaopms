<?php
declare(strict_types=1);
/**
 * The install duckdb view file of install module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     install
 * @link        https://www.zentao.net
 */
namespace zin;

$icons = array
(
    'loading' => 'spinner-indicator',
    'ok'      => 'check-circle',
    'fail'    => 'close'
);

$iconClass = array
(
    'loading' => 'spin',
    'ok'      => 'text-success',
    'fail'    => 'text-danger'
);

$duckdb = array
(
    'loading' => $lang->install->installingDuckdb,
    'ok'      => $lang->install->installedDuckdb,
    'fail'    => $lang->install->installedFail,
);

$extension = array
(
    'loading' => $lang->install->installingExtension,
    'ok'      => $lang->install->installedExtension,
    'fail'    => $lang->install->installedFail,
);

$fnGenerateInfo = function($type, $stus, $show = false) use ($icons, $iconClass, $duckdb, $extension)
{
    return p
    (
        setClass("$type-$stus" . ($show ? '' : ' hidden')),
        icon
        (
            setClass($iconClass[$stus]),
            $icons[$stus]
        ),
        $$type[$stus]
    );
};

div
(
    setID('installDuckdb'),
    $fnGenerateInfo('duckdb', 'loading', $duckdbStatus == 'loading'),
    $fnGenerateInfo('duckdb', 'ok', $duckdbStatus == 'ok'),
    $fnGenerateInfo('duckdb', 'fail', $duckdbStatus == 'fail'),
    $fnGenerateInfo('extension', 'loading', $extensionStatus == 'loading'),
    $fnGenerateInfo('extension', 'ok', $extensionStatus == 'ok'),
    $fnGenerateInfo('extension', 'fail', $extensionStatus == 'fail'),
    span
    (
        setClass('help text-warning hidden'),
        $lang->install->duckdbFail,
        a(set::href($config->bi->duckdbHelp), $config->bi->duckdbHelp)
    )
);
