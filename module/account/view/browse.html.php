<?php
/**
 * The browse view file of account module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      pengjiangxiu <pengjiangxiu@cnezsoft.com>
 * @package     account
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->account->confirmDelete)?>
<?php js::set('browseType', $browseType)?>
<style>
.modal-header .modal-title {font-weight: bold !important;}
.modal-body #ajaxForm {margin-right: 20px;}
.c-actions .btn+.btn {margin-left: 0px !important;;}
</style>
<div id='mainMenu' class='clearfix'>
  <div class='pull-left btn-toolbar'>
    <?php echo html::a(inlink('browse'), "<span class='text'>{$lang->account->all}</span>", '', "class='btn btn-link btn-active-text' id='allTab'")?>
    <a href='#' id='bysearchTab' class='btn btn-link querybox-toggle'><i class='icon-search icon'></i>&nbsp;<?php echo $lang->account->byQuery;?></a>
  </div>
  <?php if(common::hasPriv('account', 'create')):?>
  <div class="btn-toolbar pull-right" id='createActionMenu'>
    <?php
    $misc = "class='btn btn-primary'";
    echo html::a(inLink('create', '', '', true), "<i class='icon icon-plus'></i>" . $lang->account->create, '', "class='btn btn-primary' data-toggle='modal'");
    ?>
  </div>
  <?php endif;?>
</div>
<div id='queryBox' class='cell <?php if($browseType =='bysearch') echo 'show';?>' data-module='account'></div>

<div id='mainContent' class='main-row'>
  <div class='main-col main-table' id='accountList'>
    <?php if(empty($accountList)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->account->empty;?></span>
        <?php if(common::hasPriv('account', 'create')) echo html::a(inLink('create', '', '', true), "<i class='icon icon-plus'></i>" . $lang->account->create, '', "class='btn btn-info' data-toggle='modal'");?>
      </p>
    </div>
    <?php else:?>
    <table class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
          <th class='w-60px'>   <?php common::printOrderLink('id',         $orderBy, $vars, $lang->idAB);?></th>
          <th class='text-left'><?php common::printOrderLink('name',       $orderBy, $vars, $lang->account->name);?></th>
          <th class='w-100px'>  <?php common::printOrderLink('provider',   $orderBy, $vars, $lang->account->provider);?></th>
          <th class='text-left'><?php common::printOrderLink('account',    $orderBy, $vars, $lang->account->account);?></th>
          <th class='w-110px'>  <?php common::printOrderLink('email',      $orderBy, $vars, $lang->account->email);?></th>
          <th class='w-110px'>  <?php common::printOrderLink('mobile',     $orderBy, $vars, $lang->account->mobile);?></th>
          <th class='w-100px'>  <?php common::printOrderLink('createdBy',  $orderBy, $vars, $lang->account->createdBy);?></th>
          <th class='w-80px'>   <?php echo $lang->actions?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($accountList as $account):?>
        <tr class='text-left'>
          <td><?php printf('%03d', $account->id);?></td>
          <td title='<?php echo $account->name?>'><?php echo html::a($this->inlink('view', "id=$account->id", 'html'), $account->name);?></td>
          <td><?php echo zget($lang->serverroom->providerList, $account->provider);?></td>
          <td><?php echo $account->account;?></td>
          <td><?php echo $account->email;?></td>
          <td><?php echo $account->mobile;?></td>
          <td><?php echo $account->createdBy;?></td>
          <td class='c-actions'>
            <?php
            common::printLink('account','edit', "id={$account->id}&from=self", "<i class='icon-common-edit icon-edit'> </i>", '', "data-toggle='modal' class='btn' title='{$lang->edit}'", '', true);
            if(common::hasPriv('account', 'delete', $account))
            {
                $deleteURL = $this->createLink('account', 'delete', "id=$account->id");
                echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"accountList\",confirmDelete)", '<i class="icon-trash"></i>', '', "class='btn' title='{$lang->delete}'");
            }
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </div>
</div>
<script>
$(function()
{
    <?php if($browseType == 'bymodule'):?>
    $('#module<?php echo $param?>').closest('li').addClass('active');
    <?php endif;?>
    if(browseType == 'bysearch') $.toggleQueryBox(true);
})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
