<?php
declare(strict_types=1);
namespace zin;

class dynamic extends wg
{
    protected static array $defineProps = array(
        'dynamics?: array',
        'users?: array',
        'className?: string',
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public function getStatusClass(object $dynamic): string
    {
        $action     = strtolower($dynamic->action);
        $objectType = strtolower($dynamic->objectType);

        // if($dynamic->major) return 'active';

        if($objectType == 'release' && $action == 'opened') return 'trophy';
        if($objectType == 'project' && $action == 'closed') return 'trophy';

        if(strpos($action, 'open')   !== false || strpos($action, 'activate') !== false) return 'active';
        if(strpos($action, 'finish') !== false || strpos($action, 'resolve')  !== false) return 'green';
        if(strpos($action, 'assign') !== false || strpos($action, 'collect')  !== false) return 'yellow';

        return '';
    }

    protected function dynamicItem(object $dynamic, array $users): wg
    {
        $dynamicLabel = zget($dynamic, 'dynamicLabel', '');
        if(empty($dynamicLabel)) $dynamicLabel = zget($dynamic, 'actionLabel', '');

        $dynamicClass = $this->getStatusClass($dynamic);
        return li
        (
            setClass($dynamicClass),
            div
            (
                span
                (
                    setClass('dynamic-tag p-1'),
                    $dynamic->date
                ),
                div
                (
                    setClass('dynamic-text flex flex-nowrap justify-between items-center'),
                    div
                    (
                        setClass('clip p-1'),
                        zget($users, $dynamic->actor),
                        span
                        (
                            setClass('text-gray px-1'),
                            $dynamicLabel
                        ),
                        span($dynamic->objectLabel, setClass('pr-1')),
                        a
                        (
                            set::href($dynamic->objectLink),
                            set::title($dynamic->objectName),
                            $dynamic->objectName
                        )
                    ),
                    $dynamicClass == 'trophy' ? h::img
                    (
                        set::src('static/svg/trophy.svg'),
                        setClass('ml-2'),
                        set::width(30)
                    ) : null
                )
            )
        );
    }

    protected function build(): wg
    {
        $dynamicListView = h::ul
        (
            setClass('dynamic dynamic-tag-left pt-0 overflow-x-hidden'),
            setClass($this->prop('className')),
        );

        $users    = $this->prop('users', (array)data('users'));
        $dynamics = $this->prop('dynamics', (array)data('dynamics'));
        foreach($dynamics as $dynamic) $dynamicListView->add($this->dynamicItem($dynamic, $users));

        return $dynamicListView;
    }
}
