<?php
namespace zin;

class fragment extends wg
{
    static $defineProps = array
    (
        'rawContent?: bool=true'
    );

    protected function build()
    {
        $css        = array(data('pageCSS'), '/*{{ZIN_PAGE_CSS}}*/');
        $js         = array('/*{{ZIN_PAGE_JS}}*/', data('pageJS'));
        $rawContent = $this->prop('rawContent');

        return array
        (
            h::css($css, setClass('zin-page-css')),
            $this->children(),
            $rawContent ? rawContent() : null,
            h::js($js, setClass('zin-page-js'))
        );
    }
}
