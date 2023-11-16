<?php
declare(strict_types=1);
/**
 * The whitelist view file of personnel module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     personnel
 * @link        https://www.zentao.net
 */
namespace zin;

$config->personnel->whitelist->actionList['unbindWhitelist']['url']  = array('module' => $module, 'method' => 'unbindWhitelist', 'params' => 'userID={id}');

$config->personnel->whitelist->dtable->fieldList['dept']['map'] = $depts;

$whitelist = initTableData($whitelist, $config->personnel->whitelist->dtable->fieldList, $this->personnel);

$cols = array_values($config->personnel->whitelist->dtable->fieldList);
$data = array_values($whitelist);

dropmenu();

featureBar
(
    set::current('all'),
    set::linkParams("objectID={$objectID}&module=whitelist&objectType={$objectType}")
);

$whitelistVars    = $module == 'program' ? "objectID={$objectID}&programID={$projectProgramID}&module={$module}&from={$from}" : "objectID={$objectID}";
$addWhitelistVars = $module == 'program' ? "objectID={$objectID}&deptID=0&copyID=0&programID={$projectProgramID}&from={$from}" : "objectID={$objectID}";
toolbar
(
    btngroup
    (
        btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set::url(helper::createLink($module, 'addWhitelist', $addWhitelistVars)),
            $lang->personnel->addWhitelist
        )
    )
);

dtable
(
    set::cols($cols),
    set::data($data),
    set::fixedLeftWidth('0.33'),
    set::footPager(usePager())
);

render();

