<?php
declare(strict_types=1);
namespace zin;

helper::import(__DIR__ . DS . 'dynamicitem.php');
class dynamic extends wg
{
    protected static array $defineProps = array(
        'dynamics?: array',
        'users?: array',
        'className?: string'
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build()
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
            $dynamicListView->add(dynamicItem::build($dynamic, $users));
        }

        return $dynamicListView;
    }
}
