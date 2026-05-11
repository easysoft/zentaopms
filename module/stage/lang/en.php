<?php
/**
 * The stage module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     stage
 * @version     $Id: en.php 4729 2013-05-03 07:53:55Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
/* Actions. */
$lang->stage->browse      = 'Stage List';
$lang->stage->browseAB    = 'Stage List';
$lang->stage->create      = 'Create Phase';
$lang->stage->batchCreate = 'Batch Create Stage';
$lang->stage->edit        = 'Edit Stage';
$lang->stage->delete      = 'Delete Stage';
$lang->stage->view        = 'Details';
$lang->stage->plusBrowse  = 'Waterfall + Phase List';
$lang->stage->setTRpoint  = 'Set TR Point';
$lang->stage->setDCPpoint = 'Set DCP Point';

/* Fields. */
$lang->stage->id        = 'ID';
$lang->stage->name      = 'Name';
$lang->stage->type      = 'Type';
$lang->stage->percent   = 'Workload %';
$lang->stage->setType   = 'Stage Type';
$lang->stage->TRpoint   = 'TR Point';
$lang->stage->DCPpoint  = 'DCP Point';
$lang->stage->TRname    = 'TR Point Name';
$lang->stage->DCPname   = 'DCP Point Name';
$lang->stage->pointFlow = 'Approval Flow';
$lang->stage->order     = 'Order';

$lang->stage->typeList['mix']     = 'Mix';
$lang->stage->typeList['request'] = 'Story';
$lang->stage->typeList['design']  = 'Design';
$lang->stage->typeList['dev']     = 'Development';
$lang->stage->typeList['qa']      = 'Test';
$lang->stage->typeList['release'] = 'Release';
$lang->stage->typeList['review']  = 'Summary and Review';
$lang->stage->typeList['other']   = 'Others';

$lang->stage->ipdTypeList['concept']   = 'Concept';
$lang->stage->ipdTypeList['plan']      = 'Plan';
$lang->stage->ipdTypeList['develop']   = 'Development';
$lang->stage->ipdTypeList['qualify']   = 'Verify';
$lang->stage->ipdTypeList['launch']    = 'Release';
$lang->stage->ipdTypeList['lifecycle'] = 'Lifecycel';

$lang->stage->viewList      = 'Phase List';
$lang->stage->noStage       = 'No phases available yet.';
$lang->stage->confirmDelete = 'Are you are you want to delete it?';

$lang->stage->error              = new stdclass();
$lang->stage->error->percentOver = 'The sum of "Workload %" cannot exceed 100%.';
$lang->stage->error->notNum      = 'The workload % must be a numeric value.';
