<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'formgroup' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formrow' . DS . 'v1.php';

class form extends wg
{
    protected static $defineProps =
    [
        'method?: string',
        'url?: string',
        'actions?: array',
        'target?: string',
        'items?: array',
        'grid?: bool',
        'labelWidth?: number',
        'submitBtnText?: string',
        'cancelBtnText?: string',
    ];

    protected static $defaultProps =
    [
        'grid'          => true,
        'method'        => 'post',
        'target'        => 'ajax',
        'actions'       => ['submit', 'cancel'],
    ];

    public function onBuildItem($item)
    {
        if(!($item instanceof item))
        {
            if($item instanceof wg) return $item;
            $item = item(set($item));
        }

        if($this->prop('grid')) return new formRow(inherit($item));

        return new formGroup(inherit($item));
    }

    protected function buildFormActions()
    {
        $actions = $this->prop('actions');
        if(empty($actions)) return NULL;

        global $lang;
        foreach($actions as $key => $action)
        {
            if($action === 'submit')     $actions[$key] = ['text' => $this->prop('submitBtnText') ?? $lang->save, 'btnType' => 'submit', 'type' => 'primary'];
            elseif($action === 'cancel') $actions[$key] = ['text' => $this->prop('cancelBtnText') ?? $lang->goback, 'url' => html::getGobackLink()];
            elseif(is_string($action))   $actions[$key] = ['text' => $action];
        }

        return toolbar
        (
            set::class('form-actions form-group gap-4 no-label'),
            set::items($actions)
        );
    }

    protected function build()
    {
        list($items, $grid, $labelWidth, $url, $target, $method, $id) = $this->prop(['items', 'grid', 'labelWidth', 'url', 'target', 'method', 'id']);

        $actions = $this->buildFormActions();
        if($grid && !empty($actions)) $actions = div(setClass('form-row'), $actions);

        $list     = is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : [];
        $children = $this->children();
        if(!empty($children)) $list = array_merge($list, $children);

        if($grid)
        {
            foreach($list as $key => $item)
            {
                if($item instanceof formGroup) $list[$key] = new formRow($item);
            }
        }
        $isAjax = $target === 'ajax';
        if($isAjax)
        {
            $target = NULL;
            if(empty($id)) $id = $this->gid;
        }
        if(empty($url)) $url = $_SERVER['REQUEST_URI'];

        return h::form
        (
            set::class('form load-indicator', $grid ? 'form-grid' : NULL, $isAjax ? 'form-ajax' : ''),
            set(['id' => $id, 'action' => $url, 'target' => $target, 'method' => $method]),
            set($this->getRestProps()),
            empty($labelWidth) ? NULL : setCssVar('form-label-width', $labelWidth),
            $list,
            $actions,
            $isAjax ? zui::ajaxForm(set::_to("#$id")) : NULL
        );
    }
}
