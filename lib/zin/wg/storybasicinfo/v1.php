<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'datalist' . DS . 'v1.php';

class storyBasicInfo extends wg
{
    protected static array $defineProps = array
    (
        'story'       => '?object',   // 当前需求。
        'product'     => '?object',   // 当前产品。
        'branches'    => '?array',    // 当前分支信息。
        'storyModule' => '?object',   // 需求分支信息。
        'hiddenPlan'  => '?bool',     // 是否隐藏计划。
        'users'       => '?array',    // 用户列表。
        'statusText'  => '?string',   // 状态信息。
        'modulePath'  => '?string'    // 模块路径。
    );

    protected function getModuleItems(object $story, object $product): array
    {
        $modulePath  = $this->prop('modulePath', data('modulePath'));
        $storyModule = $this->prop('storyModule', data('storyModule'));
        $items       = array();
        if($modulePath)
        {
            if($storyModule->branch and isset($branches[$storyModule->branch]))
            {
                $items[] = $branches[$storyModule->branch];
            }

            foreach($modulePath as $module)
            {
                $items[] = $product->shadow ? $module->name : array('text' => $module->name, 'url' => createLink('product', 'browse', "productID=$story->product&branch=$story->branch&browseType=byModule&param=$module->id"));
            }
        }
        if(!$items) $items = array('/');
        return $items;
    }

    protected function getMinStage(object $story, ?array $branches): string
    {
        global $lang;

        $minStage    = $story->stage;
        $stageList   = implode(',', array_keys($lang->story->stageList));
        $minStagePos = strpos($stageList, $minStage);
        if($story->stages and $branches)
        {
            foreach($story->stages as $stage)
            {
                if(strpos($stageList, $stage) !== false and strpos($stageList, $stage) > $minStagePos)
                {
                    $minStage    = $stage;
                    $minStagePos = strpos($stageList, $stage);
                }
            }
        }

        return $minStage;
    }

    protected function getItems(): array
    {
        global $lang, $config;

        $story = $this->prop('story', data('story'));
        if(!$story) return array();

        $product    = $this->prop('product', data('product'));
        $branches   = $this->prop('branches', data('branches'));
        $hiddenPlan = $this->prop('hiddenPlan', data('hiddenPlan'));
        $statusText = $this->prop('statusText', $story->status);
        $users      = $this->prop('users', data('users'));
        $items      = array();

        if(!$product->shadow)
        {
            $items[$lang->story->product] = hasPriv('product', 'view') ? array('control' => 'link', 'url' => createLink('product', 'view', "productID=$story->product"), 'text' => $product->name) : $product->name;
        }
        if($product->type !== 'normal')
        {
            $items[$lang->story->branch] = hasPriv('product', 'browse') ? array('control' => 'link', 'url' => createLink('product', 'browse', "productID=$story->product&branch=$story->branch"), 'text' => $branches[$story->branch]) : $branches[$story->branch];
        }
        $items[$lang->story->module] = array
        (
            'control' => 'breadcrumb',
            'items'   => $this->getModuleItems($story, $product)
        );
        if($story->type != 'requirement' and $story->parent != -1 and !$hiddenPlan)
        {
            $planTitleItems = array();
            if(isset($story->planTitle) && $story->planTitle)
            {
                foreach($story->planTitle as $planID => $planTitle)
                {
                    $planTitleItems[] = hasPriv('productplan', 'view') ? $planTitle : array
                    (
                        'url'     => createLink('plan', 'view', "planID=$planID"),
                        'text'    => $planTitle
                    );
                }
            }
            $items[$lang->story->plan] = array
            (
                'control' => 'list',
                'items'   => $planTitleItems
            );
        }
        $items[$lang->story->source] = array
        (
            'control' => 'text',
            'content' => zget($lang->story->sourceList, $story->source, ''),
            'id'      => 'sourceBox'
        );
        $items[$lang->story->sourceNote] = array
        (
            'control' => 'text',
            'content' => $story->sourceNote,
            'id'      => 'sourceNoteBox'
        );
        $items[$lang->story->status] = array
        (
            'control' => 'status',
            'class'   => 'status-story',
            'status'  => $story->URChanged ? 'changed' : $story->status,
            'text'    => $statusText
        );
        if($story->type !== 'requirement')
        {
            $items[$lang->story->stage] = array
            (
                'class' => 'stage-line',
                'text'  => zget($lang->story->stageList, $this->getMinStage($story, $branches), '')
            );
        }
        $items[$lang->story->category] = zget($lang->story->categoryList, $story->category);
        $items[$lang->story->pri] = array
        (
            'control' => 'pri',
            'pri'     => $story->pri,
            'text'    => $lang->story->priList
        );
        $items[$lang->story->estimate] = $story->estimate . $config->hourUnit;
        if(in_array($story->source, $config->story->feedbackSource))
        {
            $items[$lang->story->feedbackBy]  = $story->feedbackBy;
            $items[$lang->story->notifyEmail] = $story->notifyEmail;
        }
        $items[$lang->story->keywords]      = $story->keywords;
        $items[$lang->story->legendMailto]  = joinMailtoList($story->mailto, $users);

        return $items;
    }

    protected function build()
    {
        return new datalist
        (
            set::className('story-basic-info'),
            set::items($this->getItems())
        );
    }
}
