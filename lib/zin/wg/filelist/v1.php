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
        'showDelete?:bool=false',
        'showEdit?:bool=false',
        'extra?:string=""',
        'fileTitle?:string=""',
        'object?:object',
        'padding?:bool=true',
        'objectType?:string=""',
        'objectID?: int'
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    private function fileList(): node
    {
        global $app;

        $files        = $this->prop('files');
        $method       = $this->prop('method');
        $showDelete   = $this->prop('showDelete');
        $showEdit     = $this->prop('showEdit');
        $extra        = $this->prop('extra');
        $object       = (object)$this->prop('object');
        $fileListView = h::ul(setClass('files-list col relative'));

        foreach($files as $file)
        {
            if($file->extra != $extra) continue;
            $fileItemView = html($app->loadTarget('file')->printFile($file, $method, $showDelete, $showEdit, $object));

            $fileListView->add($fileItemView);
        }

        return $fileListView;
    }

    protected function build()
    {
        global $lang;

        $fieldset  = $this->prop('fieldset');
        $isInModal = isAjaxRequest('modal');
        $px = $isInModal ? 'px-3' : 'px-6';
        $pb = $isInModal ? 'pb-3' : 'pb-6';

        $method     = $this->prop('method');
        $showDelete = $this->prop('showDelete');
        $objectType = $this->prop('objectType');
        $objectID   = $this->prop('objectID');

        $fileDiv = div
        (
            set
            (
                array(
                    'data-method'     => $method,
                    'data-showDelete' => $showDelete,
                    'data-session'    => session_name() . '=' . session_id(),
                    'data-objectType' => $objectType,
                    'data-objectID'   => $objectID
                )
            ),
            $this->fileList()
        );

        $fileTitle = $this->prop('fileTitle') ? $this->prop('fileTitle') : $lang->files;
        return $fieldset ? new section
        (
            setClass('files', 'pt-4', 'canvas'),
            $this->prop('padding') ? setClass($px, $pb) : null,
            set::title($fileTitle),
            set($this->getRestProps()),
            to::actions
            (
                icon('paper-clip ml-1')
            ),
            $fileDiv
        ) : $fileDiv;
    }
}
