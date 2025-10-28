<?php
declare(strict_types=1);
/**
 * The view view file of devopsspace module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     devopsspace
 * @link        https://www.zentao.net
 */
namespace zin;

$repoBrowseURL = common::hasPriv('repo', 'browse') ? $this->createLink('repo', 'browse', "repoID=%s") : '';
$repoDom       = initLinkTable($repoList, $repoBrowseURL);

$artifactRepoURL = common::hasPriv('artifactrepo', 'view') ? $this->createLink('artifactrepo', 'view', "id=%s") : '';
$artifactRepoDom = initLinkTable($artifactRepoList, $artifactRepoURL);

function initLinkTable(array $listData, string $url): array
{
    $listDom = array();
    $tdDom   = array();
    foreach($listData as $id => $object)
    {
        $tdDom[] = $url ? h::td(h::a(set::href(sprintf($url, $id)), $object->name)) : h::td($object->name);

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

$membersDom = array();
if(!empty($members))
{
    foreach($members as $user)
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
            span(setClass('text-gray'), $user->account == $space->owner ? $lang->devopsspace->owner : $lang->devopsspace->team)
        );
    }
}

detailHeader
(
    to::prefix
    (
        backBtn
        (
            set::icon('back'),
            set::type('secondary'),
            set::back('devopsspace-browse'),
            $lang->goback
        ),
        label(setClass('flex-none'), $space->id),
        entityLabel
        (
            set::level(1),
            set::text($space->name)
        ),
    )
);

$repoCount         = empty($repoList) ? 0 : count($repoList);
$systemCount       = empty($systemList) ? 0 : count($systemList);
$pipelineCount     = empty($pipelineList) ? 0 : count($pipelineList);
$artifactRepoCount = empty($artifactRepoList) ? 0 : count($artifactRepoList);

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
                            setClass('text-md font-bold ml-2 clip'),
                            $space->name
                        ),
                        div
                        (
                            setClass('ml-2 flex-none row items-center gap-2'),
                            $lang->devopsspace->aclList[$space->acl],
                            icon
                            (
                                'help',
                                toggle::tooltip(array('title' => $lang->devopsspace->aclNoticeList[$space->acl])),
                                set('data-placement', 'right'),
                                set('data-type', 'white'),
                                set('data-class-name', 'text-gray border border-light'),
                                setClass('text-gray')
                            )
                        ),
                        $space->deleted ? label
                        (
                            setClass('danger-outline text-dange flex-noner ml-2'),
                            $lang->devopsspace->deleted
                        ) : null,
                    )
                ),
                div
                (
                    setClass('mt-4'),
                    div
                    (
                        set::title($space->createdDate),
                        $lang->devopsspace->createdDate . ': ' . $space->createdDate
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
                            div($lang->devopsspace->repo)
                        ),
                        cell
                        (
                            set::title($pipelineCount),
                            div(setClass('text-3xl h-10'), $pipelineCount),
                            div($lang->devopsspace->pipeline)
                        ),
                        cell
                        (
                            set::title($artifactRepoCount),
                            div(setClass('text-3xl h-10'), $artifactRepoCount),
                            div($lang->devopsspace->artifactrepo)
                        ),
                        cell
                        (
                            set::title($systemCount),
                            div(setClass('text-3xl h-10'), $systemCount),
                            div($lang->devopsspace->system)
                        )
                    )
                )
            ),
        ),
        div
        (
            setClass('flex-auto canvas flex p-4'),
            set::title(strip_tags($space->desc)),
            html($space->desc)
        ),
        common::hasPriv('repo', 'maintain') ? div
        (
            setClass('mt-4'),
            panel
            (
                set::title($lang->devopsspace->repo),
                !empty($repoDom) ? h::table
                (
                    setClass('table w-full max-w-full bordered text-center'),
                    $repoDom
                ) : div(setClass('w-full text-center'), $lang->noData)
            )
        ) : null,
    ),
    cell
    (
        setClass('w-1/3'),
        panel
        (
            to::heading
            (
                div(set('class', 'panel-title'), $lang->execution->latestDynamic)
            ),
            to::headingActions
            (
                common::hasPriv('devopsspace', 'dynamic') ? btn
                (
                    setClass('ghost text-gray font-normal'),
                    set::url(createLink('space', 'dynamic', "spaceID={$space->id}&type=all")),
                    $lang->more
                ) : null
            ),
            set::bodyClass('h-80 overflow-y-auto pt-0'),
            set::shadow(false),
            dynamic()
        ),
        div
        (
            setClass('mt-4'),
            history
            (
                set::commentUrl(createLink('action', 'comment', array('objectType' => 'devopsspace', 'objectID' => $space->id))),
                set::bodyClass('maxh-72 overflow-y-auto')
            )
        )
    )
);

$actionList = !$space->deleted ? $this->loadModel('common')->buildOperateMenu($space) : array();
foreach($actionList as $actionType => $typeActions)
{
    foreach($typeActions as $key => $action)
    {
        $actionList[$actionType][$key]['url'] = str_replace('{id}', (string)$space->id, $action['url']);
    }
}
div
(
    setClass('w-2/3 center fixed actions-menu'),
    setClass($space->deleted ? 'no-divider' : ''),
    floatToolbar
    (
        set::object($space),
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), set::back('devopsspace-browse'), $lang->goback)),
        empty($actionList['mainActions']) ? null : set::main($actionList['mainActions']),
        empty($actionList['suffixActions']) ? null : set::suffix($actionList['suffixActions'])
    )
);
