<?php
declare(strict_types=1);
/**
 * The moveextfiles view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

jsVar('result', $result);

$checkList = array();
if($result == 'success')
{
    foreach($files as $file)
    {
        $checkList[] = checkbox
        (
            on::click('checkFileClick'),
            setID($file),
            set::name("files[$file]"),
            set::text($file),
            set::checked(true)
        );
    }
}

div
(
    setID('main'),
    div
    (
        setID('mainContent'),
        formPanel
        (
            setID('moveExtFileForm'),
            setClass('bg-canvas'),
            width('1000px'),
            set::title($lang->upgrade->compatibleEXT),
            set::actions(array()),
            set::target('_self'),
            $result == 'success' ? cell
            (
                setClass('move-extfile-tip text-secondary p-4 flex flex-wrap gap-3'),
                html($lang->upgrade->moveExtFileTip)
            ) : null,
            $result == 'success' ? cell
            (
                checkbox
                (
                    on::click('checkAllClick'),
                    setID('checkAll'),
                    set::name('checkAll'),
                    set::text($lang->upgrade->fileName),
                    set::checked(true)
                )
            ) : null,
            cell
            (
                setClass('extfiles'),
                $result == 'success' ? $checkList : div
                (
                    h::code
                    (
                        setClass('bg-surface'),
                        $command
                    )
                )
            ),
            $result == 'success' ? cell
            (
                setClass('flex justify-center'),
                btn
                (
                    setClass('px-6'),
                    on::click('submit'),
                    set::type('primary'),
                    set::text($lang->upgrade->next)
                )
            ) : cell
            (
                $lang->upgrade->moveEXTFileFail,
                btn
                (
                    on::click('loadCurrentPage'),
                    setClass('px-6 ml-4'),
                    set::text($lang->refresh)
                )
            )
        )
    )
);

render('pagebase');
