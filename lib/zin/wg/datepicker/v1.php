<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

class datePicker extends wg
{
    /**
     * Build the widget.
     *
     * @access protected
     * @return wg
     */
    protected function build(): wg
    {
        return zui::datePicker
        (
            set::_class('form-group-wrapper'),
            set::icon('calendar'),
            set($this->props)
        );
    }
}
