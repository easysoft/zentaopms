<?php
declare(strict_types=1);
namespace zin;

class severityLabel extends wg
{
    protected static $defineProps = array(
        'text?:string',     //标签文本
        'level?:string|int' //严重程度等级 1|2|3|4
    );

    protected function onAddChild(mixed $child): mixed
    {
        if(is_string($child) && !$this->props->has('text'))
        {
            $this->props->set('text', $child);
            return false;
        }
    }

    private function setThemeStyle(): \zin\directive
    {
        $level = (int)$this->prop('level');
        if($level === 1) return setClass('text-danger');
        if($level === 2) return setClass('text-warning');
        if($level === 3) return setClass('text-secondary');
        return setStyle('color', 'var(--color-slate-800)');
    }

    protected function build()
    {
        $text = $this->prop('text');
        return span
        (
            setClass('severity-label'),
            $this->setThemeStyle(),
            $text
        );
    }
}
