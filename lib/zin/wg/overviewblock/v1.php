<?php
declare(strict_types=1);
/**
 * The overview block widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     zin
 * @link        https://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'blockpanel' . DS . 'v1.php';

class overviewBlock extends wg
{
    protected static array $defineProps = array(
        'id?: string',
        'title?: string',
        'block?: object',
        'groups?: array'
    );

    protected function buildCard($card): wg
    {
        $class = array('text-2xl text-center leading-relaxed num', isset($card->class) ? $card->class : '');

        return col
        (
            setClass('justify-center w-1/2'),
            empty($card->url) ? span
            (
                setClass($class),
                $card->value
            ) : a
            (
                setClass($class),
                set::href($card->url),
                $card->value
            ),
            span
            (
                setClass('text-center text-sm'),
                $card->label
            )
        );
    }

    protected function buildCards($group): wg
    {
        $cards = array();
        foreach($group->cards as $index => $card)
        {
            if($index > 0) $cards[] = divider(setClass('h-10 self-center'));
            $cards[] = $this->buildCard($card);
        }

        return row
        (
            setClass('w-1/2'),
            setStyle(array('margin-top' => '-20px')),
            $cards
        );
    }

    protected function buildBarChart($group): wg
    {
        $bars   = array();
        $labels = array();
        foreach($group->bars as $bar)
        {
            $bars[] = h::li
            (
                setStyle(array('display' => 'contents')),
                span
                (
                    set::title($bar->value),
                    setClass('block primary bg-opacity-70 w-2'),
                    setStyle(array('height' => $bar->rate))
                )
            );

            $labels[] = span
            (
                setClass('text-center text-gray text-sm'),
                $bar->label
            );
        }

        return div
        (
            setClass('bar-chart flex justify-center w-1/2'),
            col
            (
                setClass('basis-48'),
                span
                (
                    setClass('mb-3 text-sm text-gray'),
                    $group->title
                ),
                div
                (
                    setClass('border-b'),
                    h::ul
                    (
                        setClass('flex justify-around items-end w-full'),
                        setStyle(array('height' => '60px')),
                        $bars
                    )
                ),
                div
                (
                    setClass('flex justify-around mt-1.5'),
                    $labels
                )
            )
        );
    }

    protected function buildBody($groups): array
    {
        $body = array();
        foreach($groups as $group)
        {
            if($group->type == 'cards')    $body[] = $this->buildCards($group);
            if($group->type == 'barChart') $body[] = $this->buildBarChart($group);
        }

        return $body;
    }

    protected function build(): wg
    {
        list($id, $title, $block, $groups) = $this->prop(array('id', 'title', 'block', 'groups'));

        return new blockPanel
        (
            set::block($block),
            set::title($title),
            set::id($id),
            set::headingClass('border-0'),
            set::bodyClass('flex block-base p-0'),

            $this->buildBody($groups)
        );
    }
}
