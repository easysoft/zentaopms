<?php
/**
* The editmanageprivbylist view file of group module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
* @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Feilong Guo <guofeilong@easycorp.ltd>
* @package     group
* @version     $Id: editmanageprivbylist.html.php 4769 2021-07-23 11:16:21Z $
* @link        https://www.zentao.net
*/

$lang->privp = new stdclass();
$lang->privp->name    = '权限名称';
$lang->privp->view    = '视图';
$lang->privp->module  = '模块';
$lang->privp->package = '所属权限包';
$lang->privp->p1      = '依赖权限';
$lang->privp->p2      = '推荐权限';
$lang->privp->module  = '模块';
$lang->privp->desc    = '说明';
?>
<table class="table has-sort-head" id='privList'>
  <thead>
    <tr>
      <th class="c-name"><?php echo $lang->privp->name;?></th>
      <th class="c-view"><?php echo $lang->privp->view;?></th>
      <th class="c-module"><?php echo $lang->privp->module;?></th>
      <th class="c-package"><?php echo $lang->privp->package;?></th>
      <th class="c-privs"><?php echo $lang->privp->p1;?></th>
      <th class="c-privs"><?php echo $lang->privp->p2;?></th>
      <th class="c-actions-1 text-center"><?php echo $lang->actions;?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($privList as $priv):?>
    <tr>
      <td><?php echo $priv->name;?></td>
      <?php $view = isset($this->lang->navGroup->{$priv->module}) ? $this->lang->navGroup->{$priv->module} : $priv->module;?>
      <td><?php echo isset($lang->{$view}->common) ? $lang->{$view}->common : $view;?></td>
      <td><?php echo isset($lang->{$priv->module}->common) ? $lang->{$priv->module}->common : $priv->module;?></td>
      <td><?php echo zget($packages, $priv->package, '');?></td>
      <td><?php echo '';?></td>
      <td><?php echo '';?></td>
      <td class='c-actions'><?php if(common::hasPriv('group', 'editPriv')) common::printIcon('group', 'editPriv', "privID=$priv->id", '', 'list', 'edit', '', 'iframe', true);?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
<div class='table-footer'>
  <?php $pager->show('right', 'pagerjs');?>
</div>
