<?php
declare(strict_types=1);
/**
 * The object view file of ppm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     ppm
 * @link        https://www.zentao.net
 */
namespace zin;

$domBox = div
(
    featureBar
    (
        set::current($param),
        set::labelCount($objectPager->recTotal),
        set::link($this->createLink('ppm', 'view', "id={$ppm->id}&type=object&param={key}")),
    ),
    dtable
    (
        set::id('linkObjects'),
        set::cols($config->ppm->createCheck->linkObject->dtable->fieldList),
        set::userMap($users),
        set::data(array_values($linkObjects)),
        set::loadPartial(true),
        set::onRenderCell(jsRaw('window.renderObjectCell')),
        set::footPager(usePager('objectPager', '', array('recPerPage' => $objectPager->recPerPage, 'recTotal' => $objectPager->recTotal, 'linkCreator' => createLink('ppm', 'view', "id={$ppm->id}&type=object&param={$param}&recTotal={$objectPager->recTotal}&recPerPage={recPerPage}&page={page}"))))
    )
);
