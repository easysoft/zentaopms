<?php
declare(strict_types=1);
/**
 * The formBase widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

/**
 * 基础表单（formBase）部件类，支持 Ajax 提交
 * The formBase widget class
 */
class formBase extends wg
{
    protected static $defineProps = array
    (
        'id?: string',
        'method?: string',
        'url?: string',
        'actions?: array',
        'actionsClass?: string',
        'target?: string',
        'submitBtnText?: string',
        'cancelBtnText?: string',
    );

    protected static $defaultProps = array
    (
        'id'            => '$GID',
        'method'        => 'post',
        'target'        => 'ajax',
        'actions'       => ['submit', 'cancel'],
    );

    protected function buildActions(): wg|null
    {
        $actions = $this->prop('actions');
        if(empty($actions)) return null;

        global $lang;
        foreach($actions as $key => $action)
        {
            if($action === 'submit')     $actions[$key] = ['text' => $this->prop('submitBtnText') ?? $lang->save, 'btnType' => 'submit', 'type' => 'primary'];
            elseif($action === 'cancel') $actions[$key] = ['text' => $this->prop('cancelBtnText') ?? $lang->goback, 'url' => html::getGobackLink()];
            elseif(is_string($action))   $actions[$key] = ['text' => $action];
        }

        return toolbar
        (
            set::class('form-actions', $this->prop('actionsClass')),
            set::items($actions)
        );
    }

    protected function buildContent(): array|wg
    {
        return $this->children();
    }

    protected function buildProps(): array
    {
        list($url, $target, $method, $id) = $this->prop(['url', 'target', 'method', 'id']);
        return array
        (
            set::class('form load-indicator'),
            $target === 'ajax' ? set::class('form-ajax') : null,
            set(array
            (
                'id'     => $id,
                'action' => empty($url) ? $_SERVER['REQUEST_URI'] : $url,
                'target' => $target === 'ajax' ? null: $target,
                'method' => $method
            ))
        );
    }

    protected function buildAfter(): array
    {
        $after = parent::buildAfter();
        if($this->prop('target') === 'ajax') $after[] = zui::ajaxForm(set::_to('#' . $this->id()));
        return $after;
    }

    protected function build(): wg
    {
        return h::form
        (
            $this->buildProps(),
            set($this->getRestProps()),
            $this->buildContent(),
            $this->buildActions(),
        );
    }
}
