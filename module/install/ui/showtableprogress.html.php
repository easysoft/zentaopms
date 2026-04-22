<?php
declare(strict_types=1);
/**
 * The show table progress view file of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司 (ZenTao Software (Qingdao) Co., Ltd. www.chandao.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     install
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

jsVar('dbFinish', $lang->install->dbFinish);
jsVar('dbFail', $lang->install->dbFail);

$isEn  = $app->getClientLang() == 'en';
$width = $isEn ? 'w-14' : 'w-11';

$buildChanges = function() use ($sqlChanges, $lang, $width)
{
    $changes = [];
    foreach($sqlChanges as $key => $change)
    {
        $sql = json_encode($change['sql'] ?? []);
        $changes[] = row
        (
            setClass('change-item items-center gap-3'),
            setData(['key' => $key]),
            span
            (
                setClass("label gray-pale text-gray-400 px-2.5 py-1 {$width}"),
                setData(['text' => $lang->install->changeModes[$change['mode']] ?? '']),
                icon('spinner-indicator')
            ),
            span
            (
                $change['content']
            ),
            !empty($change['sql']) ? a
            (
                set::href("javascript:showSQL({$sql})"),
                icon
                (
                    setClass('text-lg text-gray-400'),
                    'fields'
                )
            ) : null
        );
    }

    return $changes;
};

div
(
    setID('main'),
    setClass('flex justify-center'),
    div
    (
        setID('mainContent'),
        setClass('my-2 py-3 w-full max-w-7xl'),
        col
        (
            setClass('container rounded-md bg-white gap-2 px-8 py-6'),
            setStyle(['width' => '1200px']),
            row
            (
                setClass('justify-between items-center gap-4'),
                row
                (
                    setClass('items-center gap-3'),
                    div
                    (
                        setClass('text-xl font-medium'),
                        $lang->install->dbProgress,
                    ),
                    div
                    (
                        setClass('text-warning'),
                        $lang->install->dbExecutingTips
                    )
                ),
                row
                (
                    setClass('items-center gap-3'),
                    span($lang->install->dbProgressLabel),
                    progressbar
                    (
                        setID('changesProgressBar'),
                        setClass('rounded-full'),
                        setStyle(['height' => '.75rem', 'width' => '16rem']),
                        set::color('rgba(var(--color-success-500-rgb), var(--tw-bg-opacity));'),
                        set::percent(0)
                    ),
                    span
                    (
                        setID('changesProgressText'),
                        '0 / ' . count($sqlChanges)
                    )
                )
            ),
            col
            (
                setID('changesBox'),
                setClass('bg-gray-100 overflow-x-hidden overflow-y-auto gap-2 p-2'),
                setStyle(['maxHeight' => 'calc(100vh - 13rem)']),
                $buildChanges
            ),
            div
            (
                setID('statusBox'),
                setClass('hidden px-4 py-3 rounded')
            ),
            contactUs(),
            div
            (
                setClass('center'),
                a
                (
                    setID('nextBtn'),
                    setClass('btn primary disabled ' . ($isEn ? 'w-28' : 'w-24')),
                    $lang->install->next
                )
            )
        )
    )
);

render('pagebase');
