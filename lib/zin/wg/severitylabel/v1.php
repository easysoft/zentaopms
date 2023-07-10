<?php
declare(strict_types=1);
namespace zin;

class severityLabel extends wg
{
    protected static array $defineProps = array(
        'text?: string',      // 标签文本
        'level?: string|int', // 严重程度等级
        'isIcon?: bool=false' // 是否显示为图标
    );

    protected function onAddChild(mixed $child): mixed
    {
        if(is_string($child) && !$this->props->has('text'))
        {
            $this->props->set('text', $child);
            return false;
        }
    }

    protected function build(): wg
    {
        $text   = $this->prop('text');
        $level  = $this->prop('level');
        $isIcon = $this->prop('isIcon');

        return span
        (
            set($this->getRestProps()),
            setClass($isIcon ? 'severity' : 'severity severity-label'),
            set('data-severity', $level),
            $text
        );
    }
}
