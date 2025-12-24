<?php
declare(strict_types=1);
/**
 * The audit view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

$reviewPrivs = array();
$viewPrivs   = array();
foreach(array_keys($lang->my->featureBar['audit']) as $type)
{
    $priv     = hasPriv($type, 'review');
    $viewPriv = $type == 'feedback' ? hasPriv($type, 'adminView') : hasPriv($type, 'view');
    if(!in_array($type, $config->my->noFlowAuditModules) && $type != 'charter') $priv = hasPriv($type, 'approvalreview');
    if($type == 'oa')
    {
        $oaModules = array('attend', 'leave', 'overtime', 'makeup', 'lieu');
        foreach($oaModules as $oaModule) $reviewPrivs[$oaModule] = hasPriv($oaModule, 'review') && hasPriv($oaModule, 'browseReview');
    }
    else
    {
        $reviewPrivs[$type] = $priv;
        $viewPrivs[$type]   = $viewPriv;
    }
}
$reviewPrivs['review']  = hasPriv('review', 'access');
$reviewPrivs['mr']      = hasPriv('mr', 'view');
$reviewPrivs['pullreq'] = hasPriv('pullreq', 'view');

$viewPrivs['review']  = hasPriv('review', 'view');
$viewPrivs['task']    = hasPriv('task', 'view');
$viewPrivs['mr']      = hasPriv('mr', 'view');
$viewPrivs['pullreq'] = hasPriv('pullreq', 'view');

jsVar('reviewLink', createLink('{module}', 'review', 'id={id}'));
jsVar('flowReviewLink', createLink('{module}', 'approvalreview', 'id={id}'));
jsVar('oaReviewLink', createLink('{module}', 'browseReview'));
jsVar('reviewPrivs', $reviewPrivs);
jsVar('viewPrivs', $viewPrivs);
jsVar('noFlowAuditModules', $config->my->noFlowAuditModules);
jsVar('projectPriv', hasPriv('review', 'assess'));
jsVar('projectReviewLink', createLink('review', 'assess', 'reviewID={id}'));
jsVar('vision', $config->vision);
jsVar('hasReviewedTip', $lang->hasReviewed);

$rawMethod = $app->rawMethod;
if($rawMethod != 'audit' && isset($lang->my->featureBar[$rawMethod]['audit'])) $lang->my->featureBar[$rawMethod] = $lang->my->featureBar[$rawMethod]['audit'];

$linkParam = "browseType={key}&param=&orderBy={$orderBy}";
if($rawMethod == 'contribute') $linkParam = "mode=$mode&$linkParam";

featureBar
(
    set::current($browseType),
    set::linkParams($linkParam)
);

if($rawMethod != 'audit') unset($config->my->audit->dtable->fieldList['actions']);
if($rawMethod == 'contribute') $config->my->audit->dtable->fieldList['title']['sortType'] = false;
if($rawMethod != 'contribute' || $browseType != 'reviewedbyme') unset($config->my->audit->dtable->fieldList['result']);

foreach($reviewList as $review)
{
    $type       = $review->type == 'projectreview' || $review->type == 'projectchange' ? 'review' : $review->type;
    $isOAObject =  strpos(",{$config->my->oaObjectType},", ",$type,") !== false ? true : false;
    $this->app->loadLang($type);

    $review->module  = $type;
    $review->typeKey = $review->type;

    if(isset($lang->{$review->type}->common)) $typeName = $lang->{$review->type}->common;
    if($review->type == 'projectreview')      $typeName = $lang->project->common;
    if(isset($flows[$review->type]))          $typeName = $flows[$review->type];
    if($type == 'story')
    {
        $typeName = $lang->SRCommon;
        if($review->storyType == 'epic')
        {
            $typeName = $lang->ERCommon;
        }
        elseif($review->storyType == 'requirement')
        {
            $typeName = $lang->URCommon;
        }
    }

    $statusList = array();
    if(isset($lang->$type->statusList)) $statusList = $lang->$type->statusList;
    if($type == 'attend')
    {
        $this->app->loadLang('attend');
        $statusList = $lang->attend->reviewStatusList;
    }
    if($type == 'charter')
    {
        $this->app->loadLang('charter');
        $statusList = $lang->charter->reviewStatusList;
    }

    if(!in_array($type, array('demand', 'story', 'testcase', 'feedback', 'review', 'charter', 'mr', 'pullreq')) && !$isOAObject)
    {
        if($rawMethod == 'audit') $statusList = $lang->approval->nodeList;

        if(isset($flows[$review->type]) && $rawMethod != 'audit') $statusList = $lang->approval->statusList;
    }

    if(in_array($type, array('mr', 'pullreq')))
    {
        $this->app->loadLang('mr');

        if(empty($review->status)) $review->status = 'notReviewed';
        $statusList = $lang->mr->approvalStatusList;
    }

    $review->type   = $typeName;
    $review->status = zget($statusList, $review->status, '');

    if($rawMethod == 'contribute' && $browseType == 'reviewedbyme')
    {
        $reviewResultList = array();
        if(isset($lang->$type)) $reviewResultList = zget($lang->$type, 'reviewResultList', array());
        if($isOAObject)         $reviewResultList = zget($lang->$type, 'reviewStatusList', array());

        $review->result = zget($reviewResultList, $review->result);
    }

    $review->module  = isset($review->storyType) ? $review->storyType : $review->module;
    $review->project = zget($projects, $review->project, '');
    $review->product = zget($products, $review->product, '');
}

$reviewList = initTableData($reviewList, $config->my->audit->dtable->fieldList, $this->my);
$sortLink   = $app->rawMethod == 'audit' ? createLink('my', 'audit', "browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}") : createLink('my', $app->rawMethod, "mode={$mode}&browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");

$cols = array_values($config->my->audit->dtable->fieldList);
$data = array_values($reviewList);

dtable
(
    set::cols($cols),
    set::data($data),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::orderBy($orderBy),
    set::sortLink($sortLink),
    set::footPager(usePager())
);

render();
