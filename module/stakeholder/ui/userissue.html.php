<?php
declare(strict_types=1);
/**
 * The user issue view file of stakeholder module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     stakeholder
 * @link        https://www.zentao.net
 */
namespace zin;

setID('stakeholderIssueModal');

modalHeader
(
    set::entityText($lang->stakeholder->userIssue), set::entityID(''), set::title(''),
);

render();
