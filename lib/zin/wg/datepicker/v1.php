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
        list($name, $id, $defaultValue) = $this->prop(array('name', 'id', 'defaultValue'));
        return zui::datePicker
        (
            set::_class('form-group-wrapper'),
            set::_map(array('value' => 'defaultValue')),
            set::icon('calendar'),
            set($this->props),
            h::formHidden($name, $defaultValue, setID($id))
        );
    }
}
