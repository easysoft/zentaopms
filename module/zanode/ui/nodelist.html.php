<?php
declare(strict_types=1);
/**
 * The nodelist view file of zanode module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     zanode
 * @link        https://www.zentao.net
 */
namespace zin;

$fieldList = $config->zanode->dtable->fieldList;
unset($fieldList['actions']);
unset($fieldList['id']);
unset($fieldList['type']);
unset($fieldList['extranet']);
unset($fieldList['hostName']);
$fieldList['name']['sort']     = true;
$fieldList['cpuCores']['sort'] = true;
$fieldList['memory']['sort']   = true;
$fieldList['diskSize']['sort'] = true;
$fieldList['osName']['sort']   = true;
$fieldList['status']['sort']   = true;

$nodeList = initTableData($nodeList, $fieldList);

to::header('');

dtable
(
    set::cols($fieldList),
    set::data($nodeList)
);

render();

