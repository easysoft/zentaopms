<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'fileSelector' . DS . 'v1.php';

class imageSelector extends fileSelector
{
    /**
     * Build the widget.
     *
     * @access protected
     * @return wg
     */
    protected function build(): wg
    {
        return zui::imageSelector(inherit($this));
    }
}
