<?php
declare(strict_types=1);
/**
 * The view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;

$isModal = isInModal();
if(!$isModal) include 'header.html.php';

$actionList = $this->loadModel('common')->buildOperateMenu($ruleSet);
foreach($actionList as $actionType => $typeActions)
{
    foreach($typeActions as $key => $action)
    {
        $actionList[$actionType][$key]['className'] = isset($action['className']) ? $action['className'] . ' ghost' : 'ghost';
        $actionList[$actionType][$key]['iconClass'] = isset($action['iconClass']) ? $action['iconClass'] . ' text-primary' : 'text-primary';
        $actionList[$actionType][$key]['url']       = str_replace('{id}', (string)$setID, $action['url']);
    }
}

$isModal ? detailHeader(to::prefix(entityLabel(set(array('level' => 2, 'text' => $lang->codescan->browse))))) : detailHeader
(
    to::prefix
    (
        backBtn
        (
            set::icon('back'),
            set::type('secondary'),
            set::back('codescan-ruleset,codescan-solutionview'),
            $lang->goback
        ),
        entityLabel(set(array('level' => 2, 'text' => $ruleSet->name))),
    ),
    !empty($actionList['mainActions']) || !empty($actionList['suffixActions']) ? to::suffix
    (
        btnGroup(set::items($actionList['mainActions'])),
        !empty($actionList['mainActions']) && !empty($actionList['suffixActions']) ? div(setClass('divider')): null,
        btnGroup(set::items($actionList['suffixActions']))
    ) : null
);

$footToolbar = array();
if($type == 'rule')
{
    array_unshift($langs, (object)array('id' => '', 'lang' => $lang->all));
    foreach($langs as $item)
    {
        $item->parent = 0;
        $item->name   = $item->lang;
        $item->url    = inLink('rulesetview', "setID={$setID}&type={$type}&language={$item->id}");
    }

    if($ruleSet->isCustom && hasPriv('codescan', 'batchunlinkrule'))
    {
        $config->codescan->dtable->fieldList['id']['checkbox'] = true;

        $footToolbar['items'][] = array(
            'className' => 'btn primary size-sm batch-btn',
            'text'      => $lang->codescan->batchUnlink,
            'data-url'  => inLink('batchUnlinkRule', "setID={$setID}")
        );
    }

    $config->codescan->dtable->fieldList['id']['type']       = 'checkID';
    $config->codescan->dtable->fieldList['actions']['width'] = '80px';
    $config->codescan->dtable->fieldList['actions']['menu']  = array('unlink');
    $config->codescan->dtable->fieldList['actions']['list']['unlink']['url'] = array('module' => 'codescan', 'method' => 'unlinkRule', 'params' => "setID={$setID}&ruleID={id}");

    if(!$ruleSet->isCustom)
    {
        $config->codescan->dtable->fieldList['actions']['list']['unlink']['hint'] = sprintf($lang->codescan->notice->defaultRuleset, $lang->codescan->unlinkRule);
    }
    $config->codescan->dtable->fieldList['lang']['map']   = $langList;
    $config->codescan->dtable->fieldList['plugin']['map'] = $pluginList;

    if($isModal) unset($config->codescan->dtable->fieldList['actions']);
    $ruleList = initTableData($ruleList, $config->codescan->dtable->fieldList);
}

$ruleTable =  $type == 'rule' || $isModal ? div
(
    setClass('w-full flex-auto'),
    on::click('#table-codescan-rulesetview_table .batch-btn > a, #table-codescan-rulesetview_table .batch-btn')->call('handleClickBatchBtn', jsRaw('$this')),
    dtable
    (
        set::sortLink(createLink('codescan', 'rulesetview', "setID={$setID}&type={$type}&language={$language}&orderBy={name}_{sortType}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
        set::orderBy($orderBy),
        set::cols($config->codescan->dtable->fieldList),
        set::data($ruleList),
        set::loadPartial(true),
        set::extraHeight('+144'),
        set::footToolbar($footToolbar),
        set::onRenderCell(jsRaw('window.renderRuleCell')),
        set::footPager(usePager())
    )
) : null;

$isModal ? $ruleTable : panel
(
    div
    (
        setID('rulesetMenu'),
        setClass('flex justify-between'),
        $headers,
        $type == 'rule' && $ruleSet->isCustom && hasPriv('codescan', 'linkrule') ? btn
        (
            set::icon('link'),
            set::type('primary'),
            set::text($lang->codescan->linkRule),
            setClass('link mr-actions flex-none'),
            setData('toggle', 'modal'),
            setData('size', 'lg'),
            set::url(inLink('linkRule', "setID={$setID}")
        )) : null
    ),
    $type == 'rule' ? div
    (
        set::bodyClass('flex'),
        $ruleTable
    ) : div
    (
        setClass('flex items-center mt-4'),
        cell
        (
            setClass('ruleset-4 ruleset-view-cell'),
            set::grow(1),
            set::align('flex-start'),
            cell
            (
                setClass('cell mb-2'),
                tableData
                (
                    item
                    (
                        set::name($lang->codescan->name),
                        span($ruleSet->name)
                    ),
                    item
                    (
                        set::name($lang->codescan->language),
                        span($ruleSet->lang ? zget($langList, $ruleSet->lang) : '')
                    ),
                    item
                    (
                        set::name($lang->codescan->ruleNum),
                        span(zget($ruleSet, 'rulesCount', 0))
                    ),
                    item
                    (
                        set::name($lang->codescan->tool),
                        span(empty($ruleSet->plugin) ? '' : zget($pluginList, $ruleSet->plugin))
                    ),
                    item
                    (
                        set::name($lang->codescan->status),
                        span(zget($lang->codescan->statusList, $ruleSet->status))
                    ),
                    item
                    (
                        set::name($lang->codescan->createdBy),
                        span(zget($users, $ruleSet->createdBy))
                    ),
                    item
                    (
                        set::name($lang->codescan->createTime),
                        span($ruleSet->createdDate)
                    ),
                    item
                    (
                        set::name($lang->codescan->updatedBy),
                        span(zget($users, $ruleSet->editedBy))
                    ),
                    item
                    (
                        set::name($lang->codescan->updateTime),
                        span($ruleSet->editedDate)
                    ),
                    item
                    (
                        set::name($lang->codescan->desc),
                        html(common::checkNotCN() && isset($ruleSet->desc_en) ? $ruleSet->desc_en : $ruleSet->desc)
                    )
                )
            ),
            $actions ? cell
            (
                setClass('cell cell-history'),
                history
                (
                    set::objectType('codescanruleset'),
                    set::objectID($setID),
                    set::commentUrl(createLink('action', 'comment', array('objectType' => 'codescanruleset', 'objectID' => $setID)))
                )
            ) : null
        )
    )
);
