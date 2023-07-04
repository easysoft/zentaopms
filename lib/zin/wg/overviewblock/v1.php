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

class overviewBlock extends wg
{
    static $defineProps = array(
        'id?: string',
        'title?: string',
        'block?: object',
        'groups?: array'
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildCard($card)
    {
        $class = 'text-2xl text-center font-bold leading-relaxed';

        return col
        (
            setClass('card justify-center w-1/2'),
            empty($card->url) ? span
            (
                setClass($class),
                $card->value
            ) : a
            (
                setClass($class . ' text-primary'),
                set::href($card->url),
                $card->value
            ),
            span
            (
                setClass('text-center'),
                $card->label
            )
        );
    }

    protected function buildCards($group)
    {
        $cards = array();
        foreach($group->cards as $card) $cards[] = $this->buildCard($card);

        return div
        (
            setClass('cards flex w-1/2'),
            $cards
        );
    }

    protected function buildBarChart($group)
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
                    setClass('block primary w-2'),
                    setStyle(array('height' => $bar->rate))
                )
            );

            $labels[] = span
            (
                setClass('text-center'),
                $bar->label
            );
        }

        return div
        (
            setClass('flex justify-center w-1/2'),
            col
            (
                span
                (
                    setClass('text-center mb-2'),
                    $group->title
                ),
                div
                (
                    setClass('border-b'),
                    h::ul
                    (
                        setClass('flex justify-around items-end w-full h-16'),
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

    protected function buildBody($groups)
    {
        $body = array();
        foreach($groups as $group)
        {
            if($group->type == 'cards')    $body[] = $this->buildCards($group);
            if($group->type == 'barChart') $body[] = $this->buildBarChart($group);
        }

        return $body;
    }

    protected function build()
    {
        list($id, $title, $block, $groups) = $this->prop(array('id', 'title', 'block', 'groups'));

        if(!$id)    $id    = $block->module . '-' . $block->code;
        if(!$title) $title = $block->title;

        return panel
        (
            setID($id),
            set::title($title),
            set::bodyClass('flex block-base p-0'),
            $this->buildBody($groups)
        );
    }
}
