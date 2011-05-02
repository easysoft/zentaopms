<?php
/**
 * The product module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->product->common = '产品视图';
$lang->product->index  = "产品首页";
$lang->product->browse = "浏览产品";
$lang->product->view   = "产品信息";
$lang->product->edit   = "编辑产品";
$lang->product->create = "新增产品";
$lang->product->read   = "产品详情";
$lang->product->delete = "删除产品";

$lang->product->roadmap   = '路线图';
$lang->product->doc       = '文档列表';

$lang->product->selectProduct   = "请选择产品";
$lang->product->saveButton      = " 保存 (S) ";
$lang->product->confirmDelete   = " 您确定删除该产品吗？";
$lang->product->ajaxGetProjects = "接口:项目列表";
$lang->product->ajaxGetPlans    = "接口:计划列表";

$lang->product->errorFormat    = '产品数据格式不正确';
$lang->product->errorEmptyName = '产品名称不能为空';
$lang->product->errorEmptyCode = '产品代号不能为空';
$lang->product->errorNoProduct = '还没有创建产品！';
$lang->product->accessDenied   = '您无权访问该产品';

$lang->product->id        = '编号';
$lang->product->company   = '所属公司';
$lang->product->name      = '产品名称';
$lang->product->code      = '产品代号';
$lang->product->order     = '排序';
$lang->product->status    = '状态';
$lang->product->desc      = '产品描述';
$lang->product->PO        = '产品负责人';
$lang->product->QM        = '测试负责人';
$lang->product->RM        = '发布负责人';
$lang->product->acl       = '访问控制';
$lang->product->whitelist = '分组白名单';

$lang->product->moduleStory = '按模块浏览';
$lang->product->searchStory = '搜索';
$lang->product->allStory    = '全部需求';
$lang->product->allProduct  = '全部产品';

$lang->product->statusList['']       = '';
$lang->product->statusList['normal'] = '正常';
$lang->product->statusList['closed'] = '结束';

$lang->product->aclList['open']    = '默认设置(有产品视图权限，即可访问)';
$lang->product->aclList['private'] = '私有项目(只有项目团队成员才能访问)';
$lang->product->aclList['custom']  = '自定义白名单(团队成员和白名单的成员可以访问)';

$lang->product->storySummary = "本页共 <strong>%s</strong> 个需求，预计 <strong>%s</strong> 个工时。";
