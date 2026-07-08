<?php
declare(strict_types=1);
/**
 * The bug view file of ppm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     ppm
 * @link        https://www.zentao.net
 */
namespace zin;

$domBox = dtable
(
    set::id('bugs'),
    set::cols($config->ppm->bug->dtable->fieldList),
    set::data(array_values($bugs)),
    set::loadPartial(true),
    set::footPager(usePager('bugPager', '', array('recPerPage' => $bugPager->recPerPage, 'recTotal' => $bugPager->recTotal, 'linkCreator' => createLink('ppm', 'view', "id={$ppm->id}&type=bug&param=&recTotal={$bugPager->recTotal}&recPerPage={recPerPage}&page={page}"))))
);
