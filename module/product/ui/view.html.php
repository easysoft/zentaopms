<?php
declare(strict_types=1);
/**
* The UI file of product module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      chen.tao <chentao@easycorp.ltd>
* @package     product
* @link        https://www.zentao.net
*/

namespace zin;

/* Flag variable for hiding product code. */
$hiddenCode    = (!isset($config->setCode) || $config->setCode == 0 || empty($product->code));
$allStoryCount = array_sum($product->stories);

$membersDom = array();
foreach($config->product->memberFields as $field)
{
    if(empty($product->$field)) continue;
    if(!isset($members[$product->$field])) continue;

    $user = $members[$product->$field];
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
        span(setClass('text-gray'), $lang->product->$field)
    );
}

div
(
    setClass('flex w-full'),
    cell
    (
        setClass('mr-3 w-2/3'),
        div
        (
            setClass('flex-auto canvas flex p-4'),
            div
            (
                setClass('text-center w-1/3 flex flex-col justify-center items-center'),
                div
                (
                    set('class', 'chart pie-chart'),
                    echarts
                    (
                        set::color(array('#2B80FF', '#E3E4E9')),
                        set::series
                        (
                            array(array('type' => 'pie', 'radius' => array('80%', '90%'), 'itemStyle' => array('borderRadius' => '40'), 'label' => array('show' => false), 'data' => array($product->storyDeliveryRate, 100 - $product->storyDeliveryRate)))
                        )
                    )->size(120, 120),
                    div
                    (
                        set::className('pie-chart-title text-center'),
                        div(span(set::className('text-2xl font-bold'), $product->storyDeliveryRate . '%')),
                        div
                        (
                            span
                            (
                                setClass('text-sm text-gray'),
                                $lang->product->storyDeliveryRate,
                                icon
                                (
                                    'help ml-1',
                                    toggle::tooltip(array('title' => $lang->product->storyDeliveryRateTip)),
                                    set('data-placement', 'bottom'),
                                    set('data-type', 'white'),
                                    set('data-class-name', 'text-gray border border-light'),
                                    setClass('text-gray ')
                                )
                            )
                        )
                    )
                ),
                div
                (
                    setClass('border w-3/4 flex justify-center items-center pl-4 py-2'),
                    div
                    (
                        setClass('w-1/3'),
                        div
                        (
                            setClass('text-lg font-bold'),
                            $allStoryCount
                        ),
                        $lang->product->totalStories
                    ),
                    div
                    (
                        setClass('w-1/3'),
                        div
                        (
                            setClass('text-lg font-bold'),
                            $product->stories['closed']
                        ),
                        $lang->story->statusList['closed']
                    ),
                    div
                    (
                        setClass('w-1/3'),
                        div
                        (
                            setClass('text-lg font-bold'),
                            $allStoryCount - $product->stories['closed']
                        ),
                        $lang->story->unclosed
                    )
                )
            ),
            div
            (
                setClass('flex-none w-2/3'),
                div
                (
                    setClass('flex items-center'),
                    label
                    (
                        setClass('rounded-full'),
                        $product->id
                    ),
                    div
                    (
                        setClass('text-md font-bold ml-2 clip'),
                        $product->name
                    ),
                    !$hiddenCode ? label
                    (
                        setClass('gray-300-outline ml-2 flex-none'),
                        $product->code
                    ) : null,
                    $product->type != 'normal' ? label
                    (
                        setClass('gray-300-outline ml-2 text-warning flex-none'),
                        $lang->product->typeList[$product->type]
                    ) : null,
                    $product->deleted ? label
                    (
                        setClass('danger-outline text-dange flex-noner ml-2'),
                        $lang->product->deleted
                    ) : null,
                    label
                    (
                        setClass("ml-2 flex-none"),
                        setClass($product->status == 'normal' ? 'text-success' : 'text-gray'),
                        $this->processStatus('product', $product)
                    ),
                    span
                    (
                        setClass('ml-2 flex-none'),
                        $lang->product->abbr->aclList[$product->acl],
                        icon
                        (
                            'help',
                            toggle::tooltip(array('title' => $lang->product->aclList[$product->acl])),
                            set('data-placement', 'right'),
                            set('data-type', 'white'),
                            set('data-class-name', 'text-gray border border-light'),
                            setClass('ml-2 mt-2 text-gray')
                        )
                    )
                ),
                div
                (
                    setClass('flex mt-4'),
                    in_array($this->config->systemMode, array('ALM', 'PLM')) && $product->program ? div
                    (
                        setClass('clip w-1/2'),
                        set::title($lang->product->program),
                        icon('program', setClass('pr-1')),
                        $product->programName
                    ) : null,
                    $product->line ? div
                    (
                        setClass('clip w-1/2'),
                        set::title($lang->product->line),
                        icon('lane', setClass('pr-1')),
                        $product->lineName
                    ) : null
                ),
                div
                (
                    set::className('detail-content mt-4'),
                    html($product->desc)
                )
            )
        ),
        div
        (
            setClass('mt-4 p-4 bg-white'),
            panel
            (
                setClass('mb-4 memberBox'),
                set::title($lang->product->manager),
                div(setClass('flex flex-wrap member-list pt-2'), $membersDom)
            ),
            panel
            (
                setClass('otherInfoBox'),
                set::title($lang->product->otherInfo),
                div
                (
                    setClass('flex flex-wrap'),
                    div
                    (
                        setClass('w-1/4 item mb-3'),
                        span(setClass('text-gray'), $lang->product->plans),
                        span(setClass('ml-2'), $product->plans)
                    ),
                    div
                    (
                        setClass('w-1/4 item mb-3'),
                        span(setClass('text-gray'), $lang->product->releases),
                        span(setClass('ml-2'), $product->releases)
                    ),
                    div
                    (
                        setClass('w-1/4 item mb-3'),
                        span(setClass('text-gray'), $lang->product->bugs),
                        span(setClass('ml-2'), $product->bugs)
                    ),
                    div
                    (
                        setClass('w-1/4 item mb-3'),
                        span(setClass('text-gray'), $lang->product->projects),
                        span(setClass('ml-2'), $product->projects)
                    ),
                    div
                    (
                        setClass('w-1/4 item mb-3'),
                        span(setClass('text-gray'), $lang->product->builds),
                        span(setClass('ml-2'), $product->builds)
                    ),
                    div
                    (
                        setClass('w-1/4 item mb-3'),
                        span(setClass('text-gray'), $lang->product->docs),
                        span(setClass('ml-2'), $product->docs)
                    ),
                    div
                    (
                        setClass('w-1/4 item mb-3'),
                        span(setClass('text-gray'), $lang->product->cases),
                        span(setClass('ml-2'), $product->cases)
                    ),
                    div
                    (
                        setClass('w-1/4 item mb-3'),
                        span(setClass('text-gray'), $lang->product->executions),
                        span(setClass('ml-2'), $product->executions)
                    )
                )
            )
        )
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
                common::hasPriv('product', 'dynamic') ? btn
                (
                    setClass('ghost text-gray font-normal'),
                    set::url(createLink('product', 'dynamic', "productID={$product->id}&type=all")),
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
                set::commentUrl(createLink('action', 'comment', array('objectType' => 'product', 'objectID' => $product->id))),
                set::bodyClass('maxh-72 overflow-y-auto')
            )
        )
    )
);

$actionMenuList = !$product->deleted ? $this->product->buildOperateMenu($product) : array();
div
(
    setClass('w-2/3 center fixed actions-menu'),
    setClass($product->deleted ? 'no-divider' : ''),
    floatToolbar
    (
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), $lang->goback)),
        !empty($actionMenuList['main']) ? set::main($actionMenuList['main']) : null,
        !empty($actionMenuList['suffix']) ? set::suffix($actionMenuList['suffix']) : null,
        set::object($product)
    )
);
