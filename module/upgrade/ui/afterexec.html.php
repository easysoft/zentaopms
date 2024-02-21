<?php
declare(strict_types=1);
/**
 * The afterexec view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

$hideHome = false;
$finish   = array();
foreach($needProcess as $processKey => $processType)
{
    $finish[$processKey] = false;
    if($processType == 'notice') continue;
    $hideHome = true;
}

jsVar('window.finish', $finish);
jsVar('window.needProcess', $needProcess);
jsVar('window.processLink', inlink('afterExec', "fromVersion=$fromVersion&processed=yes&skipMoveFile=yes"));

$tips = array();
if(!empty($needProcess['changeEngine']))
{
    $tips[] = div
    (
        setClass('flex w-full justify-center items-center'),
        icon
        (
            setClass('text-warning px-1'),
            'help'
        ),
        $lang->upgrade->needChangeEngine
    );
}
if(!empty($needProcess['search']))
{
    $tips[] = div
    (
        setClass('flex w-full justify-center items-center'),
        icon
        (
            setClass('text-warning px-1'),
            'help'
        ),
        $lang->upgrade->needBuild4Add
    );
}
if(!empty($needProcess['updateFile']))
{
    $tips[] = div
    (
        setClass('flex flex-wrap w-full'),
        row
        (
            setClass('w-full justify-center items-center'),
            icon
            (
                setClass('text-warning px-1'),
                'help'
            ),
            $lang->upgrade->updateFile
        ),
        col
        (
            setClass('w-full justify-center text-center'),
            setID('resultBox')
        )
    );
}

div
(
    setID('main'),
    setClass('pt-8'),
    div
    (
        setID('mainContent'),
        setClass('canvas px-1 mt-8 mx-auto pb-8'),
        width('800px'),
        col
        (
            setClass('message flex-wrap justify-center' . ($hideHome ? ' hidden' : '')),
            cell
            (
                setClass('flex justify-center items-end h-48 mb-4'),
                icon
                (
                    setClass('text-success'),
                    set::size('100'),
                    'check-circle'
                )
            ),
            cell
            (
                setClass('flex justify-center items-start font-bold text-lg h-12'),
                html($lang->upgrade->success)
            ),
            $showPrivTips ? cell
            (
                setClass('flex justify-center priv-tips'),
                $lang->upgrade->addTraincoursePrivTips
            ) : null,
            cell
            (
                setClass('flex justify-center'),
                btn
                (
                    setClass('px-8'),
                    setID('tohome'),
                    set::target('_self'),
                    set::url('index.php'),
                    set::type('primary'),
                    $lang->upgrade->tohome
                )
            )
        ),
        row
        (
            setClass('flex-wrap pt-6 text-gray gap-y-1'),
            $tips
        )
    )
);

render('pagebase');
