<?php
namespace zin;

class fragment extends wg
{
    protected function build()
    {
        $css = array(data('pageCSS'), '/*{{ZIN_PAGE_CSS}}*/');
        $js  = array('/*{{ZIN_PAGE_JS}}*/', data('pageJS'));

        return array
        (
            h::css($css, setClass('zin-page-css')),
            $this->children(),
            h::js($js, setClass('zin-page-js'))
        );
    }
}
