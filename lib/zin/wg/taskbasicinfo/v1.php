<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'datalist' . DS . 'v1.php';

class taskBasicInfo extends wg
{
    protected static array $defineProps = array
    (
        'task'       => '?object',   // 当前任务。
        'product'    => '?object',   // 当前产品。
        'execution'  => '?object',   // 当前执行。
        'fromBug'    => '?object',   // 当前来源 BUG。
        'users'      => '?array',    // 用户信息。
        'users'      => '?array',    // 用户列表。
        'statusText' => '?string',   // 状态信息。
        'modulePath' => '?string'    // 模块路径。
    );

    protected function getModuleItems(object $task, null|bool|object $product): array
    {
        $isInModal        = isInModal();
        $canBrowseProduct = !$isInModal && common::hasPriv('product', 'browse');
        $canViewTasks     = !$isInModal && common::hasPriv('execution', 'task');
        $modulePath       = $this->prop('modulePath', data('modulePath'));
        $items            = array();
        if($modulePath)
        {
            if($product)
            {
                $item = array('text' => $product->name);
                if($canBrowseProduct) $item['url'] = createLink('product', 'browse', "productID=$product->id");
                $items[] = $item;
            }
            foreach($modulePath as $key => $module)
            {
                $item = array('text' => $module->name);
                if($canBrowseProduct) $item['url'] = createLink('execution', 'task', "executionID=$task->execution&browseType=byModule&param=$module->id");
                $items[] = $item;
            }
        }
        if(!$items) $items = array('/');
        return $items;
    }

    protected function getItems(): array
    {
        global $lang, $config;

        $task = $this->prop('task', data('task'));
        if(!$task) return array();

        $product    = $this->prop('product', data('product'));
        $execution  = $this->prop('execution', data('execution'));
        $users      = $this->prop('users', data('users'));
        $fromBug    = $this->prop('fromBug', data('fromBug'));
        $statusText = $this->prop('statusText', $task->status);

        $items = array();
        if($execution->multiple)
        {
            $items[$lang->task->execution] = array('control' => 'text', 'text' => $execution->name, 'title' => $execution->name);
            if(!isInModal())
            {
                $items[$lang->task->execution]['control'] = 'link';
                $items[$lang->task->execution]['url']     = createLink('execution', 'view', "executionID=$execution->id");
            }
        }

        $items[$lang->task->module] = array
        (
            'control' => 'breadcrumb',
            'items' => $this->getModuleItems($task, $product)
        );

        if($config->edition == 'max' && $execution->type == 'stage')
        {
            $items[$lang->task->design] = array('control' => 'text', 'text' => $task->designName, 'title' => $task->designName, 'control' => 'link', 'url' => createLink('design', 'view', "designID=$task->design"));
        }

        if($config->vision != 'lite')
        {
            $items[$lang->task->fromBug] = array
            (
                'control'   => 'entityTitle',
                'object'    => $fromBug,
                'type'      => 'bug',
                'url'       => true,
                'inline'    => true,
                'linkProps' => array('data-toggle' => 'modal', 'data-size' => 'lg')
            );
        }

        $items[$lang->task->assignedTo] = zget($users, $task->assignedTo, '');
        $items[$lang->task->type] = zget($lang->task->typeList, $task->type, $task->type);

        $items[$lang->task->status] = array
        (
            'control' => 'status',
            'class'   => 'task-status',
            'status'  => $task->status,
            'text'    => $statusText
        );

        $items[$lang->task->progress] = "$task->progress %";

        $items[$lang->task->pri] = array
        (
            'control' => 'pri',
            'pri'     => $task->pri,
            'text'    => $lang->task->priList
        );

        $items[$lang->task->keywords] = $task->keywords;
        $items[$lang->story->mailto]  = joinMailtoList($task->mailto, $users);

        return $items;
    }

    protected function build()
    {
        return new datalist
        (
            set::className('task-basic-info'),
            set::items($this->getItems())
        );
    }
}
