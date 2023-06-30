<?php
declare(strict_types=1);
namespace zin;

class dynamic extends wg
{
    protected static $defineProps = array(
        'dynamics?: array',
        'users?: array',
        'className?: string',
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function dynamicItem(object $dynamic, array $users): wg
    {
        return li
        (
            setClass($dynamic->major ? 'active': ''),
            div
            (
                span(
                    setClass('timeline-tag'),
                    $dynamic->date
                ),
                div(
                    setClass('timeline-text clip'),
                    zget($users, $dynamic->actor),
                    span
                    (
                        setClass('text-gray px-1'),
                        $dynamic->dynamicLabel
                    ),
                    span($dynamic->objectLabel, setClass('pr-1')),
                    a
                    (
                        set::href($dynamic->objectLink),
                        set::title($dynamic->objectName),
                        $dynamic->objectName
                    )
                )
            )
        );
    }

    protected function build(): wg
    {
        $dynamicListView = h::ul
        (
            setClass('timeline timeline-tag-left pt-0 overflow-x-hidden'),
            setClass($this->prop('className')),
        );

        $users    = $this->prop('users', (array)data('users'));
        $dynamics = $this->prop('dynamics', (array)data('dynamics'));
        foreach($dynamics as $dynamic) $dynamicListView->add($this->dynamicItem($dynamic, $users));

        return $dynamicListView;
    }
}
