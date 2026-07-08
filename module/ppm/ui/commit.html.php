<?php
declare(strict_types=1);
/**
 * The commit view file of ppm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     ppm
 * @link        https://www.zentao.net
 */
namespace zin;

$domBox = dtable
(
    set::id('commit'),
    set::userMap($users),
    set::cols($config->ppm->commitLogs->dtable->fieldList),
    set::data(array_values($commitLogs)),
    set::loadPartial(true),
    set::footPager(usePager('commitPager', '', array('recPerPage' => $commitPager->recPerPage, 'recTotal' => $commitPager->recTotal, 'linkCreator' => createLink('ppm', 'view', "id={$ppm->id}&type=commit&param=&recTotal={$commitPager->recTotal}&recPerPage={recPerPage}&page={page}"))))
);
