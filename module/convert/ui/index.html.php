<?php
declare(strict_types=1);
/**
 * The convert jira view file of convert module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     convert
 * @link        https://www.zentao.net
 */
namespace zin;

panel
(
    setID('importJira'),
    set::title($lang->convert->jira->importJira),
    div
    (
        setClass('flex justify-between panel-form text-center mx-auto size-sm p-8'),
        div
        (
            setClass('border border-hover rounded-md cursor-pointer open-url p-4 w-72 h-28'),
            set(array('data-url' => createLink('convert', 'importJiraNotice', 'type=db'))),
            div
            (
                setClass('text-xl font-bold mb-4'),
                $lang->convert->jira->importFromDB
            ),
            div
            (
                setClass('text-gray mb-4'),
                $lang->convert->jira->dbDesc
            )
        ),
        div
        (
            setClass('border border-hover rounded-md cursor-pointer open-url p-4 w-72 h-28'),
            set(array('data-url' => createLink('convert', 'importJiraNotice', 'type=file'))),
            div
            (
                setClass('text-xl font-bold mb-4'),
                $lang->convert->jira->importFromFile
            ),
            div
            (
                setClass('text-gray mb-4'),
                $lang->convert->jira->fileDesc
            )
        )
    )
);

render();
