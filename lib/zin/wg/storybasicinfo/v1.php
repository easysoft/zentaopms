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
        global $app, $config;

        $modulePath  = $this->prop('modulePath', data('modulePath'));
        $storyModule = $this->prop('storyModule', data('storyModule'));
        $items       = array();
        $isInLite    = $config->vision == 'lite';
        if($modulePath)
        {
            if($storyModule->branch and isset($branches[$storyModule->branch]))
            {
                $items[] = $branches[$storyModule->branch];
            }

            foreach($modulePath as $module)
            {
                $url = commonModel::hasPriv('product', 'browse') ? createLink('product', 'browse', "productID=$story->product&branch=$story->branch&browseType=byModule&param=$module->id") : '';
                if($isInLite) $url = commonModel::hasPriv('projectstory', 'story') ? createLink('projectstory', 'story', "projectID={$app->session->project}&productID=$story->product&branch=$story->branch&browseType=byModule&param=$module->id") : '';
                $items[] = $product->shadow || empty($url) ? $module->name : array('text' => $module->name, 'url' => $url);
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
        $gradePairs = $this->prop('gradePairs', data('gradePairs'));
        $roadmaps   = $this->prop('roadmaps', data('roadmaps'));
        $demand     = $this->prop('demand', data('demand'));
        $showGrade  = $this->prop('showGrade', data('showGrade'));
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
        if(!empty($story->demand) && !empty($demand) && $story->parent <= 0)
        {
            $demandHtml = div
            (
                setClass('flex'),
                hasPriv('demand', 'view') ? a
                (
                    $demand->title,
                    set::href(helper::createLink('demand', 'view', "demandID=$story->demand")),
                    set::title($demand->title),
                    setClass('basis-52 text-clip mr-2.5'),
                    setData('toggle', 'modal'),
                    setData('size', 'lg')
                ) : $demand->title,
                $demand->status == 'active' && $story->demandVersion < $demand->version && common::hasPriv($story->type, 'processStoryChange') ? span
                (
                    ' (',
                    $lang->story->storyChange . ' ',
                    a
                    (
                        setClass('btn primary-pale border-primary size-xs ajax-submit'),
                        set::href(createLink($story->type, 'processStoryChange', "storyID={$story->id}")),
                        $lang->confirm,
                    ),
                    ')'
                ) : null,
            );

            $items[$lang->story->upstreamDemand] = array
            (
                'control' => 'div',
                'content' => $demandHtml
            );

        }
        if(isset($story->parentName))
        {
            $storyHtml = hasPriv($story->parentType, 'view') ? div
            (
                setClass('flex'),
                a
                (
                    $story->parentName,
                    set::href(helper::createLink($story->parentType, 'view', "storyID=$story->parent")),
                    set::title($story->parentName),
                    setClass('basis-52 text-clip mr-2.5'),
                    setData('toggle', 'modal'),
                    setData('size', 'lg')
                ),
                $story->parentChanged && common::hasPriv($story->parentType, 'processStoryChange') ? span
                (
                    ' (',
                    $lang->story->storyChange . ' ',
                    a
                    (
                        setClass('btn primary-pale border-primary size-xs'),
                        set::href(createLink($story->type, 'processStoryChange', "storyID={$story->id}")),
                        $lang->confirm,
                    ),
                    ')'
                ) : null,
            ) : $story->parentName;

            $items[$lang->story->parent] = array
            (
                'children' => $storyHtml
            );

        }
        if($showGrade)
        {
            $items[$lang->story->grade] = array
            (
                'control' => 'text',
                'content' => zget($gradePairs, $story->grade)
            );
        }
        if($config->edition == 'ipd' && $story->type != 'story')
        {
            $items[$lang->story->roadmap] = hasPriv('roadmap', 'view') ? array
            (
                'control' => 'link',
                'url'     => createLink('roadmap', 'view', "roadmapID=$story->roadmap"),
                'text'    => zget($roadmaps, $story->roadmap)
            ) : zget($roadmaps, $story->roadmap, '');
        }
        if(!$hiddenPlan)
        {
            $planTitleItems = array();
            if(isset($story->planTitle) && $story->planTitle)
            {
                foreach($story->planTitle as $planID => $planTitle)
                {
                    $planTitleItems[] = hasPriv('productplan', 'view') ? array
                    (
                        'control' => 'link',
                        'url'     => !in_array($config->vision, array('lite', 'or')) ? createLink('productplan', 'view', "planID=$planID") : null,
                        'text'    => $planTitle . ' '
                    ) : $planTitle;
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
            'content' => zget($lang->{$story->type}->sourceList, $story->source, ''),
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
        $items[$lang->story->stage] = array
        (
            'control' => 'text',
            'class'   => 'stage-line',
            'text'    => zget($lang->{$story->type}->stageList, $this->getMinStage($story, $branches), '')
        );
        $items[$lang->story->category] = zget($lang->{$story->type}->categoryList, $story->category);
        $items[$lang->story->pri] = array
        (
            'control' => 'pri',
            'pri'     => $story->pri,
            'text'    => $lang->{$story->type}->priList
        );
        $items[$lang->story->estimate] = $story->estimate . $config->hourUnit;
        if(in_array($story->source, $config->story->feedbackSource))
        {
            $items[$lang->story->feedbackBy]  = $story->feedbackBy;
            $items[$lang->story->notifyEmail] = $story->notifyEmail;
        }
        $items[$lang->story->keywords]      = $story->keywords;
        $items[$lang->story->legendMailto]  = joinMailtoList($story->mailto, $users);

        if($config->vision == 'lite')
        {
            unset($items[$lang->story->product]);
            unset($items[$lang->story->branch]);
            unset($items[$lang->story->plan]);
            unset($items[$lang->story->source]);
            unset($items[$lang->story->sourceNote]);
            unset($items[$lang->story->stage]);
            unset($items[$lang->story->category]);
        }

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
