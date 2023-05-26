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
$hiddenCode = (!isset($config->setCode) || $config->setCode == 0);

/* Link for float actions. */
$goBackLink = $this->session->productList ? $this->session->productList : inlink('browse', "productID=$product->id");

/* Platform detail. */
function generatePlatformDetail($product, $branches, $lang)
{
    if($product->type != 'platform') return null;

    $branchItemList = array();
    foreach($branches as $branchName) $branchItemList[] = array('text' => $branchName);
    $branchItemList[] = array
    (
        'url'  => createLink('branch', 'manage', "productID={$product->id}"),
        'icon' => 'plus',
        'text' => $lang->branch->add
    );

    return div
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
};

/* Manager Information. */
function generateManagerInfoItemList($product, $lang, $users, $reviewers)
{
    /* String of reviewer list. */
    $reviewerListStr = '';
    foreach($reviewers as $reviewer)
    {
        if(empty($reviewer)) continue;
        $reviewerListStr .= (zget($users, $reviewer) . ' ');
    }

    $fnGenerateItem = function($label, $content)
    {
        return div
        (
            setClass('w-1/2 flex'),
            div(setClass('w-1/6 item-label'), icon(setClass('ml-auto pr-1.5'), 'person'), $label),
            div(setClass('item-content'), $content)
        );
    };

    return div
    (
        setClass('detail-content flex flex-wrap'),
        $fnGenerateItem($lang->productCommon,     zget($users, $product->PO)),
        $fnGenerateItem($lang->product->release,  zget($users, $product->RD)),
        $fnGenerateItem($lang->product->qa,       zget($users, $product->QD)),
        $fnGenerateItem($lang->product->reviewer, $reviewerListStr),
    );
};

/* Basic Information. */
function generateBasicInfoItemList($product, $lang, $hiddenCode, $users)
{
    /* ACL is custom Detail */
    $whitelist = explode(',', $product->whitelist);
    foreach($whitelist as $groupID) if(isset($groups[$groupID])) echo $groups[$groupID] . '&nbsp;';
    $aclCustomDetail = ($product->acl == 'custom') ? div
        (
            setClass('w-1/2 flex'),
            div(setClass('w-1/6 item-label'), $lang->product->whitelist),
            $whitelist
        ) : null;

    /* Generate UI for each basic information item. */
    $fnGenerateItem = function($label, $content, $status = '')
    {
        return div
        (
            setClass('w-1/2 flex'),
            div(setClass('w-1/6 item-label'), span($label)),
            div(setClass('item-content'), !empty($status) ? setClass($status) : null, $content)
        );
    };

    /* The UI without product code. */
    if($hiddenCode)
    {
        return div
        (
            setClass('detail-content flex flex-wrap'),
            $fnGenerateItem($lang->product->type,                          zget($lang->product->typeList, $product->type)),
            $fnGenerateItem($lang->product->createdBy,                     zget($users, $product->createdBy)),
            $fnGenerateItem($lang->productCommon . $lang->product->status, zget($lang->product->statusList, $product->status), $product->status),
            $fnGenerateItem($lang->product->createdDate,                   formatTime($product->createdDate, DT_DATE1)),
            $fnGenerateItem($lang->product->acl,                           $lang->product->aclList[$product->acl]),
            $aclCustomDetail
        );
    }

    return div
    (
        setClass('detail-content flex flex-wrap'),
        $fnGenerateItem($lang->product->code,                          $product->code),
        $fnGenerateItem($lang->product->createdBy,                     zget($users, $product->createdBy)),
        $fnGenerateItem($lang->product->type,                          zget($lang->product->typeList, $product->type)),
        $fnGenerateItem($lang->product->createdDate,                   formatTime($product->createdDate, DT_DATE1)),
        $fnGenerateItem($lang->productCommon . $lang->product->status, zget($lang->product->statusList, $product->status), $product->status),
        $fnGenerateItem($lang->product->acl,                           $lang->product->aclList[$product->acl]),
        $aclCustomDetail
    );
};

/* Other information. */
function generateOtherInfoItemList($product, $lang)
{
    $space = common::checkNotCN() ? ' ' : '';

    $fnGenerateItem = function($label, $content)
    {
        return div(
            setClass('w-1/3 flex'),
            div(setClass('w-2/6 item-label'), span($label)),
            div(setClass('item-content'), $content)
        );
    };

    return div
    (
        setClass('detail-content flex flex-wrap'),
        $fnGenerateItem($lang->story->statusList['active'] . $space . $lang->story->common,    $product->stories['active']),
        $fnGenerateItem($lang->product->plans,                                                 $product->plans),
        $fnGenerateItem($lang->product->bugs,                                                  $product->bugs),
        $fnGenerateItem($lang->story->statusList['draft'] . $space . $lang->story->common,     $product->stories['draft']),
        $fnGenerateItem($lang->product->builds,                                                $product->builds),
        $fnGenerateItem($lang->product->docs,                                                  $product->docs),
        $fnGenerateItem($lang->story->statusList['changing'] . $space . $lang->story->common,  $product->stories['changing']),
        $fnGenerateItem($lang->product->releases,                                              $product->releases),
        $fnGenerateItem($lang->product->cases,                                                 $product->cases),
        $fnGenerateItem($lang->story->statusList['reviewing'] . $space . $lang->story->common, $product->stories['reviewing']),
        $fnGenerateItem($lang->product->projects,                                              $product->projects),
        $fnGenerateItem($lang->product->executions,                                            $product->executions),
    );
};

/* Layout. */
div
(
    setClass('flex w-full'),
    /* Product information. */
    cell
    (
        set::width('70%'),
        panel
        (
            /* Title. */
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
            /* Platform and branches list. */
            generatePlatformDetail($product, $branches, $lang),
            /* Manager list. */
            div
            (
                setClass('detail'),
                div
                (
                    setClass('detail-title'),
                    $lang->product->manager
                ),
                generateManagerInfoItemList($product, $lang, $users, $reviewers)
            ),
            /* Base information. */
            div
            (
                setClass('detail w-full'),
                div(setClass('detail-title'), $lang->product->basicInfo),
                generateBasicInfoItemList($product, $lang, $hiddenCode, $users)
            ),
            /* Other information. */
            div
            (
                setClass('detail w-full'),
                div(setClass('detail-title'), $lang->product->otherInfo),
                generateOtherInfoItemList($product, $lang)
            )
            /* Extend Fields. */
        ),
        /* Float action toolbar. */
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
    /* Action list. */
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
            set::title($lang->action->create),
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
