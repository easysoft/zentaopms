<?php
namespace zin;

class fragment extends wg
{
    protected function build()
    {
        $context = context::current();
        $css     = $context->getCssList();
        $js      = $context->getJsList();
        $imports = $context->getImportList();

        return array
        (
            empty($css) ? NULL : h::css($css),
            empty($imports) ? NULL : h::import($imports),
            $this->children(),
            empty($js) ? NULL : h::js($js)
        );
    }
}
