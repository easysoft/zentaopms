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
    to::suffix
    (
        toolbar
        (
            setClass('ml-auto'),
            btn
            (
                setClass('primary'),
                setData(array('toggle' => 'modal', 'size' => 'lg')),
                set::icon('plus'),
                set::text($lang->issue->create),
                set::url(createLink('issue', 'create', "projectID={$projectID}&from=stakeholder&owner={$stakeholder->user}"))
            )
        )
    )
);

dtable
(
    set::cols($cols),
    set::data(array_values($issueList))
);
render();
