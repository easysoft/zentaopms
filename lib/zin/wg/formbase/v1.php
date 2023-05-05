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
        'method'        => 'post',
        'target'        => 'ajax',
        'actions'       => ['submit', 'cancel'],
    );

    protected function buildActions(): wg
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
        return array();
    }

    protected function build(): wg
    {
        list($url, $target, $method, $id) = $this->prop(['url', 'target', 'method', 'id']);

        $isAjax = $target === 'ajax';
        if($isAjax)
        {
            $target = NULL;
            if(empty($id)) $id = $this->gid;
        }
        if(empty($url)) $url = $_SERVER['REQUEST_URI'];

        return h::form
        (
            set::class('form load-indicator', $isAjax ? 'form-ajax' : ''),
            set(['id' => $id, 'action' => $url, 'target' => $target, 'method' => $method]),
            set($this->getRestProps()),
            $this->buildProps(),
            $this->buildContent(),
            $this->buildActions(),
            $isAjax ? zui::ajaxForm(set::_to("#$id")) : NULL
        );
    }
}
