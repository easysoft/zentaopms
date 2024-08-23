<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'panel' . DS . 'v1.php';

class sqlBuilderWhereGroup extends wg
{
    protected static array $defineProps = array(
        'index?: int',
        'operator?: string="and"',
        'last?: bool=false'
    );

    protected function createGroup()
    {
        global $lang;
        list($index) = $this->prop(array('index'));

        return panel
        (
            setClass('w-full mb-4'),
            set::title(sprintf($lang->bi->whereGroupTitle, $index + 1)),
            set::headingClass('relative bg-gray-100'),
            set::bodyClass('bg-gray-100'),
            to::heading
            (
                div
                (
                    setClass('absolute right-0'),
                    btn
                    (
                        setClass('add-where text-primary'),
                        set::type('ghost'),
                        set('data-index', $index),
                        $lang->bi->addWhereGroup
                    ),
                    btn
                    (
                        setClass('remove-where text-primary'),
                        set::type('ghost'),
                        set('data-index', $index),
                        $lang->bi->removeWhereGroup
                    )
                )
            ),
            $this->children()
        );
    }

    protected function build()
    {
        global $lang;
        list($index, $operator, $isLast) = $this->prop(array('index', 'operator', 'last'));
        return div
        (
            setID("whereGroup$index"),
            setClass('flex col justify-center items-center'),
            $this->createGroup(),
            div
            (
                setClass('w-16 mb-4', array('hidden' => $isLast)),
                picker
                (
                    set::name("operator$index"),
                    set::required(true),
                    set::items($lang->bi->whereGroupOperatorList),
                    set::value($operator)
                )
            )
        );
    }
}
