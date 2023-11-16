<?php
declare(strict_types=1);
/**
 * The xuanxuan view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */

namespace zin;

$generateCols = function($colItems)
{
    $cols = [];
    foreach($colItems as $rowItems)
    {
        $items = [];
        foreach($rowItems as $item)
        {
            $items[] = col
            (
                height('24'),
                setClass('justify-center'),
                div($item['title']),
                div(setClass('text-2xl mt-4'), $item['value'])
            );
        }

        $cols[] = col
        (
            width('1/3'),
            $items
        );
    }
    return $cols;
};

row
(
    setClass('gap-4'),
    panel
    (
        width('1/2'),
        set::title($lang->admin->blockStatus),
        set::shadow(false),
        set::bodyClass('flex items-center text-center'),
        $generateCols
        (
            [
                [
                    ['title' => $lang->client->xxdStatus, 'value' => $lang->client->xxdStatusList[$xxdStatus]],
                    ['title' => $lang->client->polling, 'value' => $polling]
                ],
                [
                    ['title' => $runtimeLabel, 'value' => $runtimeValue],
                    ['title' => $lang->client->countUsers, 'value' => $onlineUsers]
                ],
                [
                    [
                        'title' => $lang->admin->setServer,
                        'value' => a
                        (
                            setClass('btn text-md w-20'),
                            set::href(createLink('setting', 'xuanxuan', 'type=edit')),
                            $lang->client->set
                        )
                    ]
                ]
            ]
        )
    ),
    panel
    (
        width('1/2'),
        set::title($lang->admin->blockStatistics),
        set::shadow(false),
        set::bodyClass('flex text-center'),
        $generateCols
        (
            [
                [
                    ['title' => $lang->client->totalUsers, 'value' => $totalUsers],
                    ['title' => $lang->client->message['total'], 'value' => $messages->total]
                ],
                [
                    ['title' => $lang->client->totalGroups, 'value' => $totalGroups],
                    ['title' => $lang->client->message['day'], 'value' => $messages->day]
                ],
                [
                    ['title' => $lang->client->fileSize, 'value' => html($fileSize)],
                    ['title' => $lang->client->message['hour'], 'value' => $messages->hour]
                ]
            ]
        )
    )
);

render();
