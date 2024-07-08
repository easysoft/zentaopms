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
