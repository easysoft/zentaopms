<?php
namespace zin;

class floatPreNextBtn extends wg
{
    protected static $defineProps = array(
        'preLink?:string',
        'nextLink?:string',
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }
    protected function build(): wg
    {
        $preLink  = $this->prop('preLink');
        $nextLink = $this->prop('nextLink');

        return fragment
        (
            !empty($preLink) ? btn
            (
                setID('preButton'),
                set::url($preLink),
                setClass('float-btn float-left'),
                set::icon('angle-left')
            ) : null,
            !empty($nextLink) ? btn
            (
                setID('nextButton'),
                set::url($nextLink),
                setClass('float-btn float-right'),
                set::icon('angle-right')
            ) : null,
        );
    }
}
