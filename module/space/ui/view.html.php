<?php
declare(strict_types=1);
/**
 * The view view file of space module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     space
 * @link        https://www.zentao.net
 */
namespace zin;

dropmenu
(
    set::module('space'),
    set::tab('space'),
    set::objectID($spaceID),
    set::url(createLink('space', 'ajaxGetDropMenu', "spaceID=$spaceID&module={$app->rawModule}&method={$app->rawMethod}"))
);

$repoBrowseURL = common::hasPriv('repo', 'browse') ? $this->createLink('repo', 'browse', "repoID=%s") : '';
$repoDom       = common::hasPriv('repo', 'browse') ? initLinkTable($repoList, $repoBrowseURL, $repoPairs) : null;

//$artifactRepoURL = common::hasPriv('artifactrepo', 'view') ? $this->createLink('artifactrepo', 'view', "id=%s") : '';
//$artifactRepoDom = common::hasPriv('artifactrepo', 'view') ? initLinkTable($artifactLibList, $artifactRepoURL) : null;

function initLinkTable(array $listData, string $url, array $repoPairs = []): array
{
    $listDom = array();
    $tdDom   = array();
    foreach($listData as $id => $object)
    {
        $tdDom[] = $url && isset($repoPairs[$id]) ? h::td(h::a(set::href(sprintf($url, $id)), $object->name)) : h::td($object->name);

        $tdCount = count($tdDom);
        if($tdCount == 3)
        {
            $listDom[] = h::tr($tdDom);
            $tdDom = array();
        }

        if($id == end($listData)->id)
        {
            if($tdCount == 3) continue;
            for($i = $tdCount; $i < 3; $i++)
            {
                $tdDom[] = h::td();
            }
            $listDom[] = h::tr($tdDom);
        }
    }

    return $listDom;
}

$spaceMembers = array_merge($managers, $members);
$membersDom   = array();
foreach($spaceMembers as $user)
{
    $membersDom[] = div
    (
        setClass('w-1/6 center-y'),
        avatar
        (
            setClass('primary-outline'),
            set::size('36'),
            set::text($user->realname),
            set::src($user->avatar)
        ),
        span(setClass('my-2'), $user->realname),
        span(setClass('text-gray'), zget($space->members[$user->account], 'role') == 'manager' ? $lang->space->manager : $lang->space->members)
    );
}

$repoCount         = empty($repoList) ? 0 : count($repoList);
$systemCount       = empty($systemList) ? 0 : count($systemList);
$pipelineCount     = empty($pipelineList) ? 0 : count($pipelineList);
//$artifactRepoCount = empty($artifactLibList) ? 0 : count($artifactLibList);

div
(
    setClass('flex w-full'),
    cell
    (
        setClass('left-cell mr-5 w-2/3'),
        div
        (
            setClass('flex-auto canvas flex p-4'),
            div
            (
                setClass('w-full mr-5'),
                div
                (
                    setClass('items-center'),
                    div
                    (
                        setClass('flex items-center'),
                        label
                        (
                            setClass('rounded-full'),
                            $space->id
                        ),
                        div
                        (
                            setClass('text-md font-bold ml-2 max-w-150 text-ellipsis'),
                            set::title($space->name),
                            $space->name
                        ),
                        div
                        (
                            setClass('ml-2 flex-none row items-center gap-2'),
                            $lang->space->aclList[$space->acl],
                            icon
                            (
                                'help',
                                toggle::tooltip(array('title' => $lang->space->aclNoticeList[$space->acl])),
                                set('data-placement', 'right'),
                                set('data-type', 'white'),
                                set('data-class-name', 'text-gray border border-light'),
                                setClass('text-gray')
                            )
                        ),
                        $space->deleted ? label
                        (
                            setClass('danger-outline text-dange flex-noner ml-2'),
                            $lang->space->deleted
                        ) : null,
                    )
                ),
                div
                (
                    setClass('mt-4'),
                    div
                    (
                        set::title($space->createdDate),
                        $lang->space->createdDate . ': ' . $space->createdDate
                    )
                ),
            ),
            div
            (
                setClass('flex-none w-2/3'),
                panel
                (
                    setClass('border canvas pb-2'),
                    div
                    (
                        setClass('flex-auto flex justify-around text-center items-center'),
                        cell
                        (
                            set::title($repoCount),
                            div(setClass('text-3xl h-10'), $repoCount),
                            div($lang->space->repo)
                        ),
                        cell
                        (
                            set::title($pipelineCount),
                            div(setClass('text-3xl h-10'), $pipelineCount),
                            div($lang->space->pipeline)
                        ),
                        //cell
                        //(
                        //    set::title($artifactRepoCount),
                        //    div(setClass('text-3xl h-10'), $artifactRepoCount),
                        //    div($lang->space->artifactrepo)
                        //),
                        cell
                        (
                            set::title($systemCount),
                            div(setClass('text-3xl h-10'), $systemCount),
                            div($lang->space->system)
                        )
                    )
                )
            ),
        ),
        div
        (
            setClass('flex-auto canvas flex p-4'),
            set::title(strip_tags(zget($space, 'desc', ''))),
            html($space->desc)
        ),
        common::hasPriv('repo', 'browse') ? div
        (
            setClass('mt-4'),
            panel
            (
                set::title($lang->space->repo),
                !empty($repoDom) ? h::table
                (
                    setClass('table w-full max-w-full bordered text-center'),
                    $repoDom
                ) : div(setClass('w-full text-center'), $lang->noData)
            )
        ) : null,
        //common::hasPriv('artifactrepo', 'browse') ? div
        //(
        //    setClass('mt-4'),
        //    panel
        //    (
        //        set::title($lang->space->artifactrepo),
        //        !empty($artifactRepoDom) ? h::table
        //        (
        //            setClass('table w-full max-w-full bordered text-center'),
        //            $artifactRepoDom
        //        ) : div(setClass('w-full text-center'), $lang->noData)
        //    )
        //) : null,
        div
        (
            setClass('mt-4 bg-white'),
            panel
            (
                setClass('mb-4 memberBox'),
                set::title($lang->space->team),
                div(setClass('flex flex-wrap member-list pt-2'), $membersDom)
            )
        )
    ),
    cell
    (
        setClass('w-1/3'),
        //panel
        //(
        //    to::heading
        //    (
        //        div(set('class', 'panel-title'), $lang->execution->latestDynamic)
        //    ),
        //    to::headingActions
        //    (
        //        common::hasPriv('space', 'dynamic') ? btn
        //        (
        //            setClass('ghost text-gray font-normal'),
        //            set::url(createLink('space', 'dynamic', "spaceID={$space->id}&type=all")),
        //            $lang->more
        //        ) : null
        //    ),
        //    set::bodyClass('h-80 overflow-y-auto pt-0'),
        //    set::shadow(false),
        //    dynamic()
        //),
        div
        (
            setClass('mt-4'),
            history
            (
                set::objectID($space->id),
                set::commentUrl(createLink('action', 'comment', array('objectType' => 'space', 'objectID' => $space->id))),
                set::bodyClass('overflow-y-auto')
            )
        )
    )
);

$actionList = !$space->deleted ? $this->loadModel('common')->buildOperateMenu($space, 'space') : array();
foreach($actionList as $actionType => $typeActions)
{
    foreach($typeActions as $key => $action)
    {
        $actionList[$actionType][$key]['url'] = str_replace('{id}', (string)$space->id, $action['url']);
        if($action['icon'] == 'trash' && (!empty($repoList) || !empty($artifactLibList)))
        {
            $actionList[$actionType][$key]['disabled'] = true;
            $actionList[$actionType][$key]['hint']     = $lang->space->notice->deleteFail;
        }
    }
}
div
(
    setClass('w-2/3 center fixed actions-menu'),
    setClass($space->deleted ? 'no-divider' : ''),
    floatToolbar
    (
        set::object($space),
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), set::back('space-browse'), $lang->goback)),
        empty($actionList['mainActions']) ? null : set::main($actionList['mainActions']),
        empty($actionList['suffixActions']) ? null : set::suffix($actionList['suffixActions'])
    )
);
