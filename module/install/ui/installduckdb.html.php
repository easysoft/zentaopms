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

$duckdbLang = array
(
    'loading' => $lang->install->installingDuckdb,
    'ok'      => $lang->install->installedDuckdb,
    'fail'    => $lang->install->installedFail,
);

$extLang = array
(
    'loading' => $lang->install->installingExtension,
    'ok'      => $lang->install->installedExtension,
    'fail'    => $lang->install->installedFail,
);

$fnGenerateInfo = function($type, $stus, $show = false) use ($icons, $iconClass, $duckdbLang, $extLang)
{
    return p
    (
        setClass("$type-$stus" . ($show ? '' : ' hidden')),
        icon
        (
            setClass($iconClass[$stus]),
            $icons[$stus]
        ),
        $type == 'duckdb'    ? $duckdbLang[$stus] : null,
        $type == 'ext_dm' || $type == 'ext_mysql' ? sprintf($extLang[$stus], $type) : null,
    );
};

$fnGenerateItems = function() use ($icons, $fnGenerateInfo, $duckdb, $ext_dm, $ext_mysql)
{
    $items = array();
    foreach(array_keys($icons) as $stus)
    {
        $items[] = $fnGenerateInfo('duckdb',    $stus, $stus == $duckdb);
        $items[] = $fnGenerateInfo('ext_dm',    $stus, $stus == $ext_dm);
        $items[] = $fnGenerateInfo('ext_mysql', $stus, $stus == $ext_mysql);
    }

    return $items;
};

div
(
    setID('installDuckdb'),
    $fnGenerateItems(),
    span
    (
        setClass('help text-warning hidden'),
        $lang->install->duckdbFail,
        a(set::href($config->bi->duckdbHelp), $config->bi->duckdbHelp)
    )
);
