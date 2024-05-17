<?php
declare(strict_types=1);

namespace zin;

class affected extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'tasks?: array',      // 影响的任务。
        'executions?: array', // 影响的执行。
        'teams?: array',      // 影响的团队成员。
        'bugs?: array',       // 影响的bug。
        'cases?: array',      // 影响的用例。
        'twins?: array',      // 影响的孪生需求。
        'stories?: array'     // 影响的研发需求
    );

    protected function build()
    {
        global $lang, $config;
        list($tasks, $executions, $teams, $bugs, $cases, $twins, $stories) = $this->prop(array('tasks', 'executions', 'teams', 'bugs', 'cases', 'twins', 'stories'));

        $affectedProjects  = array();
        $affectedTaskCount = 0;
        foreach($executions as $executionID => $execution)
        {
            $teamAccounts = '';
            if(isset($teams[$executionID]))
            {
                foreach($teams[$executionID] as $member) $teamAccounts .= zget(data('users'), $member->account) . ' ';
            }

            $executionTasks     = array_values(zget($tasks, $executionID, array()));
            $affectedTaskCount += count($executionTasks);
            $affectedProjects[] = h6
            (
                $execution->name,
                $teamAccounts ? h::small(icon('group'), $teamAccounts) : null
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
                empty($stories) ? null : tabPane
                (
                    to::suffix(label(count($stories))),
                    set::key('affectedStories'),
                    set::title($lang->story->affectedStories),
                    empty($stories) ? div(setClass('dtable-empty-tip'), div(setClass('text-gray'), $lang->noData)) : dtable
                    (
                        set::cols($config->story->affect->stories->fields),
                        set::data(array_values($stories)),
                        set::style(array('min-width' => '100%'))
                    )
                ),
                tabPane
                (
                    to::suffix(label(count($bugs))),
                    set::key('affectedBugs'),
                    set::title($lang->story->affectedBugs),
                    empty($bugs) ? div(setClass('dtable-empty-tip'), div(setClass('text-gray'), $lang->noData)) : dtable
                    (
                        set::cols($config->story->affect->bugs->fields),
                        set::data(array_values($bugs)),
                        set::style(array('min-width' => '100%'))
                    )
                ),
                tabPane
                (
                    to::suffix(label(count($cases))),
                    set::key('affectedCases'),
                    set::title($lang->story->affectedCases),
                    empty($cases) ? div(setClass('dtable-empty-tip'), div(setClass('text-gray'), $lang->noData)) : dtable
                    (
                        set::cols($config->story->affect->cases->fields),
                        set::data(array_values($cases)),
                        set::style(array('min-width' => '100%'))
                    )
                ),
                empty($twins) ? null : tabPane
                (
                    to::suffix(label(count($twins))),
                    set::key('affectedTwins'),
                    set::title($lang->story->affectedTwins),
                    empty($twins) ? div(setClass('dtable-empty-tip'), div(setClass('text-gray'), $lang->noData)) : dtable
                    (
                        set::cols($config->story->affect->twins->fields),
                        set::data(array_values($twins)),
                        set::style(array('min-width' => '100%'))
                    )
                )
            )
        );
    }
}
