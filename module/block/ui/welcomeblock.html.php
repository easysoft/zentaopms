<?php
declare(strict_types=1);
/**
* The welcome view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      LiuRuoGu <liuruogu@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

use zin\utils\style;

$getMeasureItem = function($data)
{
    global $lang;

    $welcomeLabel = array_merge($lang->block->welcome->assignList, $lang->block->welcome->reviewList);

    $items = array();
    foreach($data as $key => $info)
    {
        if(count($items) >= 5) break;
        $items[] = cell
        (
            div
            (
                set('class', 'text-3xl h-10'),
                !empty($info['href']) ? a(setClass('text-primary'), set('href', $info['href']), $info['number']) : span($info['number'])
            ),
            div(zget($welcomeLabel, $key, '')),
            !empty($info['delay']) ? div
            (
                set('class', 'label danger-pale circle size-sm'),
                $lang->block->delay . ' ' . $info['delay']
            ) : null
        );
    }
    return $items;
};

blockPanel
(
    set::title(false),
    set::headingClass('px-0 py-1 border-b-0'),
    set::bodyClass('p-0'),
    set::bodyProps(array('style' => array('background-image' => 'linear-gradient(90deg, var(--color-secondary-50) 0%, var(--color-canvas) 22%)'))),
    to::heading
    (
        row
        (
            setClass('flex-auto items-center p-2 gap-2'),
            cell
            (
                set::width('22%'),
                setClass('text-center font-bold text-md'),
                $todaySummary
            ),
            cell
            (
                setClass('text-sm'),
                html(sprintf($lang->block->summary->welcome, $usageDays, $finishTask, $fixBug))
            )
        )
    ),
    row
    (
        setClass('h-full items-center'),
        cell
        (
            setClass('center flex-none gap-2'),
            set::width('22%'),
            strong(sprintf($lang->block->welcomeList[$welcomeType], $app->user->realname)),
            userAvatar
            (
                set::className('welcome-avatar'),
                set('user', $this->app->user)
            ),
            $honorary ? label
            (
                setClass('rounded-full size-sm'),
                setStyle('background', 'linear-gradient(87.65deg, rgba(255, 186, 52, 0.8) -19.92%, rgba(253, 222, 164, 0.8) 112.97%)'),
                setStyle('border', '0.5px solid #FF9F46'),
                setStyle('color', '#7E5403'),
                $honorary
            ) : null,
        ),
        divider(setClass('h-10 self-center')),
        cell
        (
            setClass('px-8 h-full flex-auto'),
            tabs
            (
                set::headerClass(array('nav-tabs' => false, 'gap-x-5' => false)),
                empty($lang->block->welcome->reviewList) ? null : tabPane
                (
                    set::title($lang->block->welcome->reviewByMe),
                    div
                    (
                        set::className('flex justify-around text-center'),
                        $getMeasureItem($reviewByMe)
                    )
                ),
                tabPane
                (
                    set::title($lang->block->welcome->assignToMe),
                    set::active(true),
                    div
                    (
                        set::className('flex justify-around text-center'),
                        $getMeasureItem($assignToMe)
                    )
                )
            )
        )
    ),
    h::css
    (
        '.block-welcome .tabs-nav > .nav-item > a {padding: 0 8px; border-radius: 4px; height: 28px}',
        '.block-welcome .tabs-nav > .nav-item > a:not(.active) {font-weight: normal; color: var(--color-gray-700)}',
        '.block-welcome .tabs-nav > .nav-item > a.active {font-weight: bold; color: var(--color-gray-900); background: var(--color-primary-50)}'
    )
);
