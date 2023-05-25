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

$reviewerListStr = '';
foreach($reviewers as $reviewer)
{
    if(empty($reviewer)) continue;
    $reviewerListStr .= (zget($users, $reviewer) . ' ');
}

/* Platform detail. */
$platformDetail = null;
if($product->type == 'platform')
{
    $branchItemList = array();
    foreach($branches as $branchName)
    {
        $branchItemList[] = array('text' => $branchName);
    }
    $branchItemList[] = array
    (
        'url'  => createLink('branch', 'manage', "productID={$product->id}"),
        'icon' => 'plus',
        'text' => $lang->branch->add
    );

    $platformDetail = div
    (
        setClass('detail'),
        div(
            setClass('detail-title'),
            $lang->product->branchName['platform'],
            btn
            (
                set::url(createLink('branch', 'manage', "productID={$product->id}")),
                set::icon('more')
            )
        ),
        div(
            setClass('detail-content flex flex-wrap'),
            menu(set::items($branchItemList))
        )
    );
}

/* Basic Information. */
$hiddenCode = (!isset($config->setCode) || $config->setCode == 0);

/* ACL is custom Detail */
$whitelist = explode(',', $product->whitelist);
foreach($whitelist as $groupID) if(isset($groups[$groupID])) echo $groups[$groupID] . '&nbsp;';
$aclCustomDetail = ($product->acl == 'custom') ? div
    (
        setClass('w-1/2 flex'),
        div(setClass('w-1/6 item-label'), $lang->product->whitelist),
        $whitelist
    ) : null;

function generateBasicInfoItem($label, $content, $status = '')
{
    return div
    (
        setClass('w-1/2 flex'),
        div(setClass('w-1/6 item-label'), span($label)),
        div(setClass('item-content'), !empty($status) ? setClass($status) : null, $content)
    );
}

if($hiddenCode)
{
    $basicInfoDetail = div
    (
        setClass('detail-content flex flex-wrap'),
        generateBasicInfoItem($lang->product->type,                          zget($lang->product->typeList, $product->type)),
        generateBasicInfoItem($lang->product->createdBy,                     zget($users, $product->createdBy)),
        generateBasicInfoItem($lang->productCommon . $lang->product->status, zget($lang->product->statusList, $product->status), $product->status),
        generateBasicInfoItem($lang->product->createdDate,                   formatTime($product->createdDate, DT_DATE1)),
        generateBasicInfoItem($lang->product->acl,                           $lang->product->aclList[$product->acl]),
        $aclCustomDetail
    );
}
else
{
    $basicInfoDetail = div
    (
        setClass('detail-content flex flex-wrap'),
        generateBasicInfoItem($lang->product->code,                          $product->code),
        generateBasicInfoItem($lang->product->createdBy,                     zget($users, $product->createdBy)),
        generateBasicInfoItem($lang->product->type,                          zget($lang->product->typeList, $product->type)),
        generateBasicInfoItem($lang->product->createdDate,                   formatTime($product->createdDate, DT_DATE1)),
        generateBasicInfoItem($lang->productCommon . $lang->product->status, zget($lang->product->statusList, $product->status), $product->status),
        generateBasicInfoItem($lang->product->acl,                           $lang->product->aclList[$product->acl]),
        $aclCustomDetail
    );
}

/* Other information detail. */
$space = common::checkNotCN() ? ' ' : '';

function generateOtherInfoItem($label, $content)
{
    return div(
        setClass('w-1/3 flex'),
        div(setClass('w-2/6 item-label'), span($label)),
        div(setClass('item-content'), $content)
    );
}

$otherDetail = div
(
    setClass('detail-content flex flex-wrap'),
    generateOtherInfoItem($lang->story->statusList['active'] . $space . $lang->story->common,    $product->stories['active']),
    generateOtherInfoItem($lang->product->plans,                                                 $product->plans),
    generateOtherInfoItem($lang->product->bugs,                                                  $product->bugs),
    generateOtherInfoItem($lang->story->statusList['draft'] . $space . $lang->story->common,     $product->stories['draft']),
    generateOtherInfoItem($lang->product->builds,                                                $product->builds),
    generateOtherInfoItem($lang->product->docs,                                                  $product->docs),
    generateOtherInfoItem($lang->story->statusList['changing'] . $space . $lang->story->common,  $product->stories['changing']),
    generateOtherInfoItem($lang->product->releases,                                              $product->releases),
    generateOtherInfoItem($lang->product->cases,                                                 $product->cases),
    generateOtherInfoItem($lang->story->statusList['reviewing'] . $space . $lang->story->common, $product->stories['reviewing']),
    generateOtherInfoItem($lang->product->projects,                                              $product->projects),
    generateOtherInfoItem($lang->product->executions,                                            $product->executions),
);

/* Float actions. */
$goBackLink = $browseLink = $this->session->productList ? $this->session->productList : inlink('browse', "productID=$product->id");

/* Main content. */
div
(
    setClass('flex w-full'),
    cell
    (
        set::width('70%'),
        panel
        (
            div
            (
                setClass('detail'),
                div
                (
                    setClass('detail-title'),
                    span(setClass('label-id'), $product->id),
                    $hiddenCode ? ' ' : span(setClass('item-label'), $product->code),
                    $product->name
                )
            ),
            $platformDetail,
            /* Manager. */
            div
            (
                setClass('detail'),
                div
                (
                    setClass('detail-title'),
                    $lang->product->manager
                ),
                div
                (
                    setClass('detail-content flex flex-wrap'),
                    div
                    (
                        setClass('w-1/2 flex'),
                        div
                        (
                            setClass('w-1/6 item-label'),
                            icon(setClass('ml-auto pr-1.5'), 'person'),
                            $lang->productCommon
                        ),
                        div
                        (
                            setClass('item-content'),
                            zget($users, $product->PO)
                        )
                    ),
                    div
                    (
                        setClass('w-1/2 flex'),
                        div
                        (
                            setClass('w-1/6 item-label'),
                            icon(setClass('ml-auto pr-1.5'), 'person'),
                            $lang->product->release
                        ),
                        div
                        (
                            setClass('item-content'),
                            zget($users, $product->RD)
                        )
                    ),
                    div
                    (
                        setClass('w-1/2 flex'),
                        div
                        (
                            setClass('w-1/6 item-label'),
                            icon(setClass('ml-auto pr-1.5'), 'person'),
                            $lang->product->qa
                        ),
                        div
                        (
                            setClass('item-content'),
                            zget($users, $product->QD)
                        )
                    ),
                    div
                    (
                        setClass('w-1/2 flex'),
                        div
                        (
                            setClass('w-1/6 item-label'),
                            icon(setClass('ml-auto pr-1.5'), 'person'),
                            $lang->product->reviewer
                        ),
                        div
                        (
                            setClass('item-content'),
                            $reviewerListStr
                        )
                    )
                )
            ),
            /* Base information. */
            div
            (
                setClass('detail w-full'),
                div
                (
                    setClass('detail-title'),
                    $lang->product->basicInfo
                ),
                $basicInfoDetail
            ),
            /* Other information. */
            div
            (
                setClass('detail w-full'),
                div
                (
                    setClass('detail-title'),
                    $lang->product->otherInfo
                ),
                $otherDetail
            )
            /* Extend Fields. */
        ),
        /* Actions. */
        div
        (
            setClass('flex justify-center'),
            toolbar(
                isonlybody() ? null : btn(
                    set::icon('back'),
                    set::url($goBackLink),
                    $lang->goback
                )
            )
        )
    ),
    cell
    (
        setClass('px-4'),
        set::width('30%'),
        history
        (
            setClass('shadow canvas'),
            set::actions($actions)
        ),
        modal
        (
            set::id('comment-dialog'),
            set::title('添加备注'),
            form
            (
                set::url(createLink('action', 'comment', "objectType=product&objectID=$product->id&zin=1")),
                formGroup
                (
                    set::width('full'),
                    set::name('comment'),
                    set::control('editor')
                )
            )
        )
    )
);

render();
