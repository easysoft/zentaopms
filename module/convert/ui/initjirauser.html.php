<?php
declare(strict_types=1);
/**
 * The init jira user view file of convert module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     convert
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->convert->jira->initJiraUser),
    set::submitBtnText($lang->convert->jira->next),
    set::backUrl(inlink('mapJira2Zentao', "method={$method}&dbName={$this->session->jiraDB}&step=4")),
    formRow
    (
        formGroup
        (
            setClass('w-1/2'),
            set::label($lang->user->password),
            set::control('password'),
            set::name('password1'),
            set::placeholder($lang->user->placeholder->passwordStrength[0]),
            set::required(true)
        ),
        formGroup
        (
            setClass('w-1/2'),
            icon('help self-center text-warning mr-1 pl-2'),
            span
            (
                setClass('self-center text-gray'),
                $lang->convert->jira->passwordNotice
            )
        )
    ),
    formGroup
    (
        setClass('grow-0 w-1/2'),
        set::label($lang->user->password2),
        set::control('password'),
        set::name('password2'),
        set::required(true)
    ),
    formRow
    (
        formGroup
        (
            setClass('w-1/2'),
            set::label($lang->user->group),
            set::name('group'),
            set::items($groups)
        ),
        formGroup
        (
            setClass('w-1/2'),
            icon('help self-center text-warning mr-1 pl-2'),
            span
            (
                setClass('self-center text-gray'),
                $lang->convert->jira->groupNotice
            )
        )
    )
);

render();
