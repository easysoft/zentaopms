<?php
declare(strict_types=1);
/**
 * The tasks view file of story module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     story
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::titleClass('icon icon-bar-chart'));

$tableData = initTableData($cases, $config->story->caseTable->fieldList, $this->story);

dtable
(
    set::groupDivider(true),
    set::userMap($users),
    set::cols($config->story->caseTable->fieldList),
    set::data($tableData)
);
