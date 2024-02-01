<?php
declare(strict_types=1);
/**
* The bugs view file of testcase module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     testcase
* @link        https://www.zentao.net
*/

namespace zin;

set::title($lang->testcase->bugs);

$cols = $config->testcase->bug->dtable->fieldList;
foreach(array_keys($cols) as $colName) $cols[$colName]['sortType'] = false;

dtable
(
    set::cols($cols),
    set::data(array_values($bugs)),
    set::userMap($users),
    set::orderBy($orderBy)
);

render();
