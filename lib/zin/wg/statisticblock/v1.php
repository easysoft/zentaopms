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

/**
 * 统计类区块（statisticBlock）部件类
 * The statisticBlock widget class
 */
class statisticBlock extends blockPanel
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array
    (
        'items: array',   // 列表项目，格式为：{id: string, text: string, url: string, activeUrl: string}[]
        'active?: string' // 当前激活的项目 ID。
    );

    /**
     * Get page CSS code.
     *
     * @return string|false
     * @access protected
     */
    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    /**
     * Build navigator.
     *
     * @param string $id
     * @param array  $items
     * @param string $active
     * @param bool   $longBlock
     * @return wg|null
     */
    protected function buildNav($id, $items, $active, $longBlock): wg|null
    {
        if(empty($items)) return null;

        $navItems = array();
        $hasPrev  = true;
        $hasNext  = true;
        foreach($items as $index => $item)
        {
            if($item['id'] == $active)
            {
                if($index == 0) $hasPrev = false;
                if($index + 1 == count($items)) $hasNext = false;
            }
            $navItems[] = li
            (
                setClass('nav-item group' . ($item['id'] == $active ? ' active' : '')),
                a
                (
                    toggle::tab(array('target' => "#blockTab_{$id}_{$item['id']}")),
                    setClass('block-statistic-nav-item flex-auto min-w-0', $item['id'] == $active ? 'active' : ''),
                    span(setClass('text clip'), $item['text'])
                ),
                !$longBlock ? span(setClass('block-statistic-nav-title text text-primary font-bold clip'), $item['text']) : null,
                !empty($item['url']) ? a
                (
                    $longBlock ? setClass('block-statistic-nav-url top-0 right-0 opacity-0 group-hover:opacity-100 transition-opacity') : null,
                    set::href($item['url']),
                    icon('import rotate-270 primary-pale rounded-full w-5 h-5 center')
                ) : null
            );
        }

        return div
        (
            setClass('flex-none block-statistic-nav border-r', $longBlock ? 'bg-surface w-52' : 'relative w-full'),
            nav
            (
                setClass('scrollbar-thin scrollbar-hover', $longBlock ? 'overflow-y-auto overflow-x-hidden h-full' : 'overflow-x-auto overflow-y-hidden p-2'),
                set::stacked($longBlock),
                $navItems
            ),
            $longBlock ? null : array
            (
                btn(span(setClass('chevron-left scale-75')), setClass('block-statistic-nav-btn size-sm square w-6 transition-opacity canvas text-primary rounded-full shadow-lg absolute top-3 left-2'), setData('type', 'prev'), set::disabled(!$hasPrev)),
                btn(span(setClass('chevron-right scale-75')), setClass('block-statistic-nav-btn size-sm square w-6 transition-opacity canvas text-primary rounded-full shadow-lg absolute top-3 right-2'), setData('type', 'next'), set::disabled(!$hasNext)),
                bind::click('.block-statistic-nav-btn', implode('', array
                (
                    'const disabled = "disabled";',
                    'const type = $target.data("type");',
                    'const $nextItem = $element.find(".nav-item>.active").parent()[type]();',
                    'if(!$nextItem.length) return $target.addClass(disabled);',
                    '$element.find(".nav-item>.active").parent().removeClass("active");',
                    '$element.find(".nav-item>.active").removeClass("active").removeClass("scroll-into-view");',
                    '$nextItem.scrollIntoView({block: "nearest", inline: "center", behavior: "smooth", ifNeeded: false}).find("a")[0].click();',
                    '$nextItem.addClass("active").addClass("scroll-into-view");',
                    '$element.find(".block-statistic-nav-btn[data-type=\'prev\']").toggleClass(disabled, !$nextItem.prev().length);',
                    '$element.find(".block-statistic-nav-btn[data-type=\'next\']").toggleClass(disabled, !$nextItem.next().length);'
                )))
            )
        );
    }

    /**
     * Build tabs panes.
     *
     * @param string $id
     * @param array  $items
     * @param string $active
     * @param bool   $longBlock
     * @return wg|null
     */
    protected function buildPanes($id, $items, $active, $longBlock): wg|null
    {
        if(empty($items))
        {
            global $lang;
            return center
                (
                    setClass('text-gray flex-auto'),
                    $lang->noData
                );
        }

        $panes = array();
        foreach($items as $item)
        {
            $isActive = $item['id'] == $active;
            $panes[] = div
            (
                isset($item['activeUrl']) ? setData('active', $item['activeUrl']) : null,
                setData('name', $item['id']),
                setID("blockTab_{$id}_{$item['id']}"),
                setClass('tab-pane h-full', $isActive ? 'active' : 'need-load'),
                $isActive ? $this->children() : null
            );
        }

        return div
        (
            setClass('flex-auto block-statistic-panes overflow-clip'),
            $panes,
            on::show('.tab-pane.need-load', 'const $target = $(target); const blockID = $target.closest(".dashboard-block").attr("data-id"); const url = $(target).data("active"); loadPartial(url, `#${target.id}>*`, {id: "blockTab_' . $id . '"}); $("#dashboard").dashboard("update", {id: blockID, fetch: url, needLoad: false});')
        );
    }

    /**
     * Build panel body.
     *
     * @return wg
     */
    protected function buildBody(): wg
    {
        list($id, $title, $block, $longBlock, $items, $active, $bodyProps) = $this->prop(array('id', 'title', 'block', 'longBlock', 'items', 'active', 'bodyProps'));
        if($longBlock === null) $longBlock = data('longBlock');

        return div
        (
            setClass('panel-body p-0 block-statistic', $longBlock ? 'row' : 'col', $this->prop('bodyClass')),
            set($bodyProps),
            $this->buildNav($id, $items, $active, $longBlock),
            $this->buildPanes($id, $items, $active, $longBlock)
        );
    }
}
