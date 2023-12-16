<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'section' . DS . 'v1.php';

class fileList extends wg
{
    protected static array $defineProps = array(
        'files?:array',
        'fieldset?:bool=true',
        'method?:string="view"',
        'showDelete?:bool=true',
        'showEdit?:bool=true',
        'object?:object',
        'padding?:bool=true'
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    private function fileList(): wg
    {
        global $app;

        $files        = $this->prop('files');
        $method       = $this->prop('method');
        $showDelete   = $this->prop('showDelete');
        $showEdit     = $this->prop('showEdit');
        $object       = (object)$this->prop('object');
        $fileListView = h::ul(setClass('files-list col relative'));

        foreach($files as $file)
        {
            $fileItemView = html($app->loadTarget('file')->printFile($file, $method, $showDelete, $showEdit, $object));

            $fileListView->add($fileItemView);
        }

        return $fileListView;
    }

    protected function build(): wg
    {
        global $lang;

        $fieldset  = $this->prop('fieldset');
        $isInModal = isAjaxRequest('modal');
        $px = $isInModal ? 'px-3' : 'px-6';
        $pb = $isInModal ? 'pb-3' : 'pb-6';

        $method     = $this->prop('method');
        $showDelete = $this->prop('showDelete');

        $fileDiv = div
        (
            set
            (
                array(
                    'data-method' => $method,
                    'data-showDelete' => $showDelete,
                    'data-session' => session_name() . '=' . session_id()
                )
            ),
            $this->fileList()
        );

        return $fieldset ? new section
        (
            setClass('files', 'pt-4', 'canvas'),
            $this->prop('padding') ? setClass($px, $pb) : null,
            set::title($lang->files),
            to::actions
            (
                icon('paper-clip')
            ),
            $fileDiv
        ) : $fileDiv;
    }
}
