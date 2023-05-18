<?php
declare(strict_types=1);
namespace zin;

class riskLabel extends wg
{
    protected static $defineProps = array(
        'text?:string', // 标签文本
        'level?:string' // 风险等级：高('h')、中('m')、低('l')
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

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
        $level = $this->prop('level');
        if($level === 'h')  return setClass('text-danger');
        if($level === 'm')  return setClass('text-warning');
        return setStyle('color', 'var(--color-slate-800)');
    }

    protected function build(): wg
    {
        $text = $this->prop('text');
        return span
        (
            setClass('risk-label'),
            $this->setThemeStyle(),
            $text
        );
    }
}
