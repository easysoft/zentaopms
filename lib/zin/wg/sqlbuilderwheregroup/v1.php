<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'panel' . DS . 'v1.php';

class sqlBuilderWhereGroup extends wg
{
    protected static array $defineProps = array(
        'index?: int'
    );

    protected function build()
    {
        global $lang;
        list($index) = $this->prop(array('index'));
        return panel
        (
            set::title(sprintf($lang->bi->whereGroupTitle, $index + 1)),
            set::headingClass('relative'),
            to::heading
            (
                div
                (
                    setClass('absolute right-0'),
                    btn
                    (
                        setClass('add-where'),
                        set::type('ghost'),
                        set('data-index', $index),
                        $lang->bi->addWhereGroup
                    ),
                    btn
                    (
                        setClass('remove-where'),
                        set::type('ghost'),
                        set('data-index', $index),
                        $lang->bi->removeWhereGroup
                    )
                )
            ),
            $this->children()
        );
    }
}
