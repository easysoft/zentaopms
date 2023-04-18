<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'panel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formgroup' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formrow' . DS . 'v1.php';

class form extends panel
{
    protected static $defineProps =
    [
        'method?: string',
        'url?: string',
        'actions?: array',
        'target?: string',
        'items?: array',
        'grid?: bool',
        'labelWidth?: number'
    ];

    protected static $defaultProps =
    [
        'class'         => 'rounded-md shadow ring-0 canvas px-4 pb-4 mb-4',
        'size'          => 'lg',
        'grid'          => true,
        'method'        => 'post',
        'target'        => 'ajax',
        'actions'       => ['save', 'back']
    ];

    protected function created()
    {
        $this->setDefaultProps(['title' => data('title')]);
    }

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
            if($action === 'save')       $actions[$key] = ['text' => $lang->save, 'btnType' => 'submit', 'type' => 'primary'];
            elseif($action === 'back')   $actions[$key] = ['text' => $lang->goback, 'url' => \html::getGobackLink()];
            elseif($action === 'cancel') $actions[$key] = ['text' => $lang->cancel, 'url' => 'javascript:history.go(-1)'];
            elseif(is_string($action))   $actions[$key] = ['text' => $action];
        }

        return toolbar
        (
            set::class('form-actions form-group gap-4'),
            set::items($actions)
        );
    }

    protected function buildForm()
    {
        list($items, $grid, $labelWidth, $url, $target, $method) = $this->prop(['items', 'grid', 'labelWidth', 'url', 'target', 'method']);

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

        return h::form
        (
            setClass('form', $grid ? 'form-grid' : NULL, $target === 'ajax' ? 'form-ajax' : ''),
            set(['action' => $url, 'target' => $target === 'ajax' ? NULL : $target, 'method' => $method]),
            empty($labelWidth) ? NULL : setCssVar('form-label-width', $labelWidth),
            $list,
            $actions
        );
    }

    protected function buildBody()
    {
        return div
        (
            setClass('panel-body'),
            $this->buildForm(),
        );
    }
}
