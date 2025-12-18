<?php
declare(strict_types=1);
/**
 * The browse review flow view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

$data = initTableData($flowList, $config->repo->dtable->reviewFlow->fieldList);
dtable
(
    set::cols($config->repo->dtable->reviewFlow->fieldList),
    set::data($data),
    set::footPager(usePager('pager'))
);
