<?php
declare(strict_types=1);
/**
 * The statistic block widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     zin
 * @link        https://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'blockpanel' . DS . 'v1.php';

class statisticBlock extends wg
{
    protected static array $defineProps = array
    (
        'id?: string',
        'title?: string',
        'block?: object',
        'longBlock?: bool',
        'items: array', // {id: string, text: string, url: string}
        'active?: string'
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildNav($items, $active, $longBlock): wg|null
    {
        if(empty($items)) return null;

        $navItems = array();
        $gid = $this->gid;
        foreach($items as $item)
        {
            $navItems[] = li
            (
                setClass('nav-item group'),
                a
                (
                    toggle::tab(array('target' => "#tab_{$gid}_{$item['id']}")),
                    setClass('block-statistic-nav-item flex-auto min-w-0', $item['id'] === $active ? 'active' : ''),
                    span(setClass('text clip'), $item['text'])
                ),
                (isset($item['url']) && !empty($item['url'])) ? a
                (
                    setClass('block-statistic-nav-url top-0 right-0 opacity-0 group-hover:opacity-100 transition-opacity'),
                    set::href($item['url']),
                    icon('import rotate-270 primary-pale rounded-full w-5 h-5 center'),
                ) : null
            );
        }

        return div
        (
            setClass('flex-none block-statistic-nav scrollbar-hover scrollbar-thin bg-surface overflow-y-auto overflow-x-hidden border-r', $longBlock ? 'w-52' : 'w-full'),
            nav
            (
                set::stacked(true),
                $navItems
            )
        );
    }

    protected function buildPanes($items, $active, $longBlock): wg|null
    {
        if(empty($items)) return null;

        $panes = array();
        $gid = $this->gid;
        foreach($items as $item)
        {
            $isActive = $item['id'] === $active;
            $panes[] = div
            (
                setID("tab_{$gid}_{$item['id']}"),
                setClass('tab-pane h-full', $isActive ? 'active' : ''),
                $isActive ? $this->children() : null
            );
        }

        return div
        (
            setClass('flex-auto block-statistic-panes'),
            $panes
        );
    }

    protected function build(): wg
    {
        list($id, $title, $block, $longBlock, $items, $active) = $this->prop(array('id', 'title', 'block', 'longBlock', 'items', 'active'));
        if($longBlock === null) $longBlock = data('longBlock');

        return new blockPanel
        (
            set::block($block),
            set::title($title),
            set::id($id),
            set::longBlock($longBlock),
            set::bodyClass('block-statistic flex p-0'),
            set($this->getRestProps()),
            $this->buildNav($items, $active, $longBlock),
            $this->buildPanes($items, $active, $longBlock)
        );
    }
}
