<?php
declare(strict_types=1);
namespace zin;

class fragment extends wg
{
    protected static array $defineProps = array
    (
        'rawContent?: bool'
    );

    protected function build(): array
    {
        $css        = array(data('pageCSS'), '/*{{ZIN_PAGE_CSS}}*/');
        $js         = array('/*{{ZIN_PAGE_JS}}*/', data('pageJS'));
        $rawContent = $this->prop('rawContent', !zin::$rawContentCalled);

        return array
        (
            h::css($css, setClass('zin-page-css')),
            $this->children(),
            $rawContent ? rawContent() : null,
            h::js($js, setClass('zin-page-js'))
        );
    }
}
