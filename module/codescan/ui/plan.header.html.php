<?php
declare(strict_types=1);
namespace zin;
global $app;

$actionList = $this->loadModel('common')->buildOperateMenu($plan);
foreach($actionList as $actionType => $typeActions)
{
    foreach($typeActions as $key => $action)
    {
        $actionList[$actionType][$key]['className'] = isset($action['className']) ? $action['className'] . ' ghost' : 'ghost';
        $actionList[$actionType][$key]['iconClass'] = isset($action['iconClass']) ? $action['iconClass'] . ' text-primary' : 'text-primary';
        $actionList[$actionType][$key]['url']       = str_replace(array('{id}', '{repoID}', '{repo}'), array($planID, $serviceRepoID, $serviceRepoID), $action['url']);
    }
}

if(!empty($repoID)) dropmenu(set::objectID($repoID), set::tab('repo'));

detailHeader
(
    to::prefix
    (
        backBtn
        (
            set::icon('back'),
            set::type('secondary'),
            set::back('codescan-plan,codescan-task'),
            $lang->goback
        ),
        entityLabel(set(array('entityID' => $planID, 'level' => 2, 'text' => $plan->name))),
    ),
    !empty($actionList['mainActions']) || !empty($actionList['suffixActions']) ? to::suffix
    (
        btnGroup(set::items($actionList['mainActions'])),
        !empty($actionList['mainActions']) && !empty($actionList['suffixActions']) ? div(setClass('divider')): null,
        btnGroup(set::items($actionList['suffixActions']))
    ) : null
);

$headers = nav
(
    setClass('flex-auto'),
    common::hasPriv('codescan', 'task') ? li
    (
        setID('scanTask'),
        setClass('nav-item'),
        a
        (
            icon('todo', setClass('text-special')),
            $lang->codescan->task,
            set::href(inLink('planview', "serviceRepoID={$serviceRepoID}&planID={$planID}&repoID={$repoID}&type=task")),
            set('data-app', $app->tab),
            $type == 'task' ? setClass('active') : null
        )
    ) : null,
    li
    (
        setClass('nav-item'),
        a
        (
            icon('flag', setClass('text-special')),
            $lang->codescan->planView,
            set::href(inLink('planview', "serviceRepoID={$serviceRepoID}&planID={$planID}&repoID={$repoID}&type=view")),
            set('data-app', $app->tab),
            $type == 'view' ? setClass('active') : null
        )
    )
);
