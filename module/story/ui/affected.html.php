<?php
declare(strict_types=1);
namespace zin;

if($story->type == 'story')
{
    $getAffectedTabs = function($story, $users)
    {
        global $lang, $config;
        $affectedProjects  = array();
        $affectedTaskCount = 0;
        foreach($story->executions as $executionID => $execution)
        {
            $teams = '';
            if(isset($story->teams[$executionID]))
            {
                foreach($story->teams[$executionID] as $member) $teams .= zget($users, $member->account) . ' ';
            }

            $executionTasks     = array_values(zget($story->tasks, $executionID, array()));
            $affectedTaskCount += count($executionTasks);
            $affectedProjects[] = h6
            (
                $execution->name,
                $teams ? h::small(icon('group'), $teams) : null
            );
            $affectedProjects[] = empty($executionTasks) ? div(setClass('dtable-empty-tip'), div(setClass('text-gray'), $lang->noData)) : dtable
            (
                set::cols($config->story->affect->projects->fields),
                set::data($executionTasks)
            );
        }

        return formGroup
        (
            setClass('w-full'),
            set::label($lang->story->checkAffection),
            set::required(false),
            tabs
            (
                setClass('w-full'),
                tabPane
                (
                    to::suffix(label($affectedTaskCount)),
                    set::key('affectedProjects'),
                    set::title($lang->story->affectedProjects),
                    set::active(true),
                    empty($affectedProjects) ? div(setClass('dtable-empty-tip'), div(setClass('text-gray'), $lang->noData)) : $affectedProjects
                ),
                tabPane
                (
                    to::suffix(label(count($story->bugs))),
                    set::key('affectedBugs'),
                    set::title($lang->story->affectedBugs),
                    empty($story->bugs) ? div(setClass('dtable-empty-tip'), div(setClass('text-gray'), $lang->noData)) : dtable
                    (
                        set::cols($config->story->affect->bugs->fields),
                        set::data(array_values($story->bugs)),
                        set::style(array('min-width' => '100%'))
                    )
                ),
                tabPane
                (
                    to::suffix(label(count($story->cases))),
                    set::key('affectedCases'),
                    set::title($lang->story->affectedCases),
                    empty($story->cases) ? div(setClass('dtable-empty-tip'), div(setClass('text-gray'), $lang->noData)) : dtable
                    (
                        set::cols($config->story->affect->cases->fields),
                        set::data(array_values($story->cases)),
                        set::style(array('min-width' => '100%'))
                    )
                ),
                empty($story->twins) ? null : tabPane
                (
                    to::suffix(label(count($story->twins))),
                    set::key('affectedTwins'),
                    set::title($lang->story->affectedTwins),
                    empty($story->twins) ? div(setClass('dtable-empty-tip'), div(setClass('text-gray'), $lang->noData)) : dtable
                    (
                        set::cols($config->story->affect->twins->fields),
                        set::data(array_values($story->twins)),
                        set::style(array('min-width' => '100%'))
                    )
                )
            )
        );
    };
}
else
{
    $getAffectedTabs = function($story)
    {
        global $lang, $config;
        return formGroup
        (
            setClass('w-full'),
            set::label($lang->story->checkAffection),
            set::required(false),
            tabs
            (
                setClass('w-full'),
                tabPane
                (
                    set::active(true),
                    to::suffix(label(count($story->children))),
                    set::key('affectedChildren'),
                    set::title($lang->story->children),
                    $story->children ? dtable
                    (
                        set::cols($config->story->affect->children->fields),
                        set::data(array_values($story->children))
                    ): null
                )
            )
        );
    };
}
