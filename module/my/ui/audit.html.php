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
foreach(array_keys($lang->my->featureBar['audit']) as $type) $reviewPrivs[$type] = hasPriv($type, 'review');

jsVar('viewLink',   createLink('{module}', 'view',   'id={id}'));
jsVar('reviewLink', createLink('{module}', 'review', 'id={id}'));
jsVar('reviewPrivs', $reviewPrivs);

$rawMethod = $app->rawMethod;
if($rawMethod != 'audit' && isset($lang->my->featureBar[$rawMethod]['audit'])) $lang->my->featureBar[$rawMethod] = $lang->my->featureBar[$rawMethod]['audit'];

$linkParam = "browseType={key}&param=&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
if($rawMethod == 'contribute') $linkParam = "mode=$mode&$linkParam";

featurebar
(
    set::current($browseType),
    set::linkParams($linkParam)
);

if($rawMethod != 'audit') unset($config->my->audit->dtable->fieldList['actions']);
if($rawMethod == 'contribute') $config->my->audit->dtable->fieldList['title']['sortType'] = false;
if($rawMethod != 'contribute' || $browseType != 'reviewedbyme') unset($config->my->audit->dtable->fieldList['result']);

foreach($reviewList as $review)
{
    $type       = $review->type == 'prejectreview' ? 'review' : $review->type;
    $isOAObject =  strpos(",{$config->my->oaObjectType},", ",$type,") !== false ? true : false;

    $review->module = $review->type;

    if(isset($lang->{$review->type}->common)) $typeName = $lang->{$review->type}->common;
    if($type == 'story')                      $typeName = $review->storyType == 'story' ? $lang->SRCommon : $lang->URCommon;
    if($review->type == 'projectreview')      $typeName = $lang->project->common;
    if(isset($flows[$review->type]))          $typeName = $flows[$review->type];

    $statusList = array();
    if(isset($lang->$type->statusList)) $statusList = $lang->$type->statusList;
    if($type == 'attend')
    {
        $this->app->loadLang('attend');
        $statusList = $lang->attend->reviewStatusList;
    }

    if(!in_array($type, array('demand', 'story', 'testcase', 'feedback', 'review')) && !$isOAObject)
    {
        if($rawMethod == 'audit') $statusList = $lang->approval->nodeList;

        if(isset($flows[$review->type]) && $rawMethod != 'audit') $statusList = $lang->approval->statusList;
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

    $module = $type;
    $method = 'review';
    $params = "id=$review->id";

    if($isOAObject) $method = 'view';
    if(!in_array($module, array('demand', 'story', 'testcase', 'feedback'))) $method = 'approvalreview';

    if($module == 'review')
    {
        $method  = 'assess';
        $params .= "&from={$rawMethod}";

        unset($config->my->audit->actionList['review']['data-toggle']);
    }

    $config->my->audit->actionList['review']['url'] = createLink($module, 'view', "id={$review->id}");
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
