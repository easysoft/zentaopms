<?php
declare(strict_types=1);
namespace zin;

class dynamic extends wg
{
    protected static array $defineProps = array(
        'dynamics?: array',
        'users?: array',
        'className?: string'
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

        if(strpos($action, 'assigned') !== false) return 'blue';
        if(strpos($action, 'finished') !== false || strpos($action, 'resolved') !== false || ($action == 'closed' && $objectType != 'product')) return 'green';

        return '';
    }

    protected function dynamicItem(object $dynamic, array $users): wg
    {
        global $config;
        $dynamicLabel = zget($dynamic, 'dynamicLabel', '');
        if(empty($dynamicLabel)) $dynamicLabel = zget($dynamic, 'actionLabel', '');

        $objectLabel = array();
        if($dynamic->action != 'login' && $dynamic->action != 'logout')
        {
            $objectLabel[] = span
            (
                $dynamic->objectLabel
            );
            $objectID = $dynamic->objectID && strpos(',module,chartgroup,', ",$dynamic->objectType,") !== false && strpos(',created,edited,moved,', "$dynamic->action") !== false ? trim($dynamic->extra, ',') : $dynamic->objectID;
            $objectLabel[] = $objectID ? span
            (
                setClass('label gray-300-outline mx-2 font-sm'),
                $objectID
            ) : null;

            if(($config->edition == 'max' && strpos($config->action->assetType, ",{$dynamic->objectType},") !== false) && empty($dynamic->objectName))
            {
                $objectLabel[] = span("#{$dynamic->objectID}");
            }
            elseif(empty($dynamic->objectID) and $dynamic->extra)
            {
                $objectLabel[] = span("#{$dynamic->extra}");
            }
            elseif(empty($dynamic->objectLink))
            {
                $objectLabel[] = span($dynamic->objectName);
            }
            else
            {
                $objectLabel[] = a
                (
                    set::href($dynamic->objectLink),
                    set::title($dynamic->objectName),
                    $dynamic->objectName
                );
            }
        }

        $dynamicClass = $this->getStatusClass($dynamic);
        return li
        (
            setClass($dynamicClass),
            div
            (
                span
                (
                    setClass('dynamic-tag p-1 text-gray'),
                    isset($dynamic->time) ? $dynamic->time : $dynamic->date
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
                        $objectLabel
                    ),
                    $dynamicClass == 'trophy' ?
                    div
                    (
                        setClass('w-0 h-0'),
                        h::img
                        (
                            set::src('static/svg/trophy.svg'),
                            setClass('ml-2')
                        )
                    ) : null
                )
            )
        );
    }

    protected function build(): wg
    {
        $users    = $this->prop('users', (array)data('users'));
        $dynamics = $this->prop('dynamics', (array)data('dynamics'));
        $hasTime  = !empty($dynamisc) && isset(reset($dynamics)->time) ? 'has-time' : '';

        $dynamicListView = h::ul
        (
            setClass('dynamic dynamic-tag-left pt-0 overflow-hidden has-time'),
            setClass($this->prop('className'))
        );

        foreach($dynamics as $dynamic)
        {
            if($dynamic->action == 'adjusttasktowait') continue;
            $dynamicListView->add($this->dynamicItem($dynamic, $users));
        }

        return $dynamicListView;
    }
}
