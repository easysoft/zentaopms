<?php
/**
 * The stage module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     stage
 * @version     $Id: zh-cn.php 4729 2013-05-03 07:53:55Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
/* Actions. */
$lang->stage->browse      = '瀑布阶段列表';
$lang->stage->create      = '新建';
$lang->stage->batchCreate = '批量新建';
$lang->stage->edit        = '编辑';
$lang->stage->delete      = '删除';
$lang->stage->view        = '阶段详情';
$lang->stage->plusBrowse  = '融合瀑布阶段列表';

/* Fields. */
$lang->stage->id      = 'ID';
$lang->stage->name    = '阶段名称';
$lang->stage->type    = '阶段类型';
$lang->stage->percent = '工作量占比';
$lang->stage->setType = '阶段类型';

$lang->stage->typeList['mix']     = '综合';
$lang->stage->typeList['request'] = '需求';
$lang->stage->typeList['design']  = '设计';
$lang->stage->typeList['dev']     = '开发';
$lang->stage->typeList['qa']      = '测试';
$lang->stage->typeList['release'] = '发布';
$lang->stage->typeList['review']  = '总结评审';
$lang->stage->typeList['other']   = '其他';

$lang->stage->ipdTypeList['concept']   = '概念';
$lang->stage->ipdTypeList['plan']      = '计划';
$lang->stage->ipdTypeList['develop']   = '开发';
$lang->stage->ipdTypeList['qualify']   = '验证';
$lang->stage->ipdTypeList['launch']    = '发布';
$lang->stage->ipdTypeList['lifecycle'] = '全生命周期';

$lang->stage->viewList      = '浏览列表';
$lang->stage->noStage       = '暂时没有阶段';
$lang->stage->confirmDelete = '您确定要执行删除操作吗？';

$lang->stage->error              = new stdclass();
$lang->stage->error->percentOver = '工作量占比累计不应当超过100%';
$lang->stage->error->notNum      = '工作量占比应当是数字';
