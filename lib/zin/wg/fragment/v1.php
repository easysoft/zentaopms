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
            h::css($css, set::id('pageCSS')),
            $this->children(),
            h::js($js, set::id('pageJS'))
        );
    }
}
