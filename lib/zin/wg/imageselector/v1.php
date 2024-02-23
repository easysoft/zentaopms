<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'fileselector' . DS . 'v1.php';

class imageSelector extends fileSelector
{
    protected static array $defaultProps = array
    (
        'mode' => 'grid'
    );

    /**
     * Build the widget.
     *
     * @access protected
     * @return node
     */
    protected function build()
    {
        return zui::imageSelector(inherit($this));
    }

    protected function created()
    {
        if(!$this->hasProp('tip'))
        {
            global $lang;
            $this->setProp('tip', $lang->uploadImagesTip);
        }

        parent::created();
    }
}
