<?php
declare(strict_types=1);
/**
 * The browse view file of group module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     group
 * @link        https://www.zentao.net
 */
namespace zin;

/* zin: Define the set::module('group') feature bar on main menu. */
featureBar
(
    set::current('all'),
);

jsVar('confirmDelete', $lang->group->confirmDelete);
$tableData = initTableData($groups, $config->group->dtable->fieldList, $this->group);
dtable
(
    set::cols(array_values($config->group->dtable->fieldList)),
    set::data($tableData),
);

/* ====== Render page ====== */
render();
