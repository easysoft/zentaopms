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

include('jiraside.html.php');

div
(
    setClass('flex'),
    panel
    (
        setClass('w-1/4 mr-4'),
        $items
    ),
    panel
    (
        setClass('flex-1 m-0 p-0 overflow-y-scroll scrollbar-thin scrollbar-hover'),
        setStyle(array('max-height' => 'calc(100vh - 130px)')),
        formPanel
        (
            set::title($lang->convert->jira->steps['user']),
            set::actionsClass('hidden'),
            formRow
            (
                formGroup
                (
                    setClass('w-1/2'),
                    set::label($lang->user->account),
                    set::control(array('control' => 'radioList', 'inline' => true)),
                    set::name('mode'),
                    set::items($lang->convert->jiraUserMode),
                    set::value(!empty($_SESSION['jiraUser']) ? zget($this->session->jiraUser, 'mode', 'account') : 'account')
                ),
                formGroup
                (
                    setClass('w-1/2'),
                    span
                    (
                        icon('help self-center text-warning mr-1 pl-2'),
                        setClass('self-center text-gray'),
                        $lang->convert->jira->accountNotice
                    )
                )
            ),
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
                    span
                    (
                        icon('help self-center text-warning mr-1 pl-2'),
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
                    set::items($groups),
                    set::value(!empty($_SESSION['jiraUser']) ? zget($this->session->jiraUser, 'group', '') : '')
                ),
                formGroup
                (
                    setClass('w-1/2'),
                    span
                    (
                        icon('help self-center text-warning mr-1 pl-2'),
                        setClass('self-center text-gray'),
                        $lang->convert->jira->groupNotice
                    )
                )
            )
        )
    )
);

render();
