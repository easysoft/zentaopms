<?php
/**
 * The view view of stakeholder module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('stakeholder', 'expectation', 'browseType=all'), '<span class="text">' . $lang->stakeholder->browse . '</span>', '', 'class="btn btn-link btn-active-text"');?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->stakeholder->search;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('stakeholder', 'createExpect', '', "<i class='icon icon-plus'></i>" . $lang->stakeholder->createExpect, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id='mainContent' class='main-row fade'>
  <div class='main-col'>
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module="stakeholder"></div>
    <?php if(!empty($expects)):?>
    <form class='main-table table-user' data-ride='table' action='' method='post' id='userListForm'>
      <table class='table has-sort-head' id='userList'>
        <thead>
        <tr>
          <?php $vars = "browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
          <th class='c-id w-60px'>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class="w-200px"><?php echo $lang->stakeholder->common;?></th>
          <th class="w-100px"><?php echo $lang->stakeholder->createdBy;?></th>
          <th class="w-120px"><?php echo $lang->stakeholder->createdDate;?></th>
          <th class='c-actions w-60px'><?php echo $lang->actions;?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($expects as $expect):?>
        <tr>
          <td class='c-id'>
            <?php echo html::a($this->createLink('stakeholder', 'viewExpect', "id=$expect->id"), sprintf('%03d', $expect->id));?>
          </td>
          <td title="<?php echo $expect->realname;?>">
          <?php echo $expect->realname;?>
          <?php if($expect->key):?>
            <i class="icon icon-star-empty"></i>
          <?php endif;?>
          </td>
          <td><?php echo zget($users, $expect->createdBy);?></td>
          <td><?php echo $expect->createdDate;?></td>
          <td class='c-actions'>
            <?php
            common::printIcon('stakeholder', 'editExpect', "id=$expect->id", $expect, 'list', 'edit', '', '', '');
            $deleteClass = common::hasPriv('stakeholder', 'deleteExpect') ? 'btn' : 'btn disabled';
            echo html::a($this->createLink('stakeholder', 'deleteExpect', "id=$expect->id"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->stakeholder->delete}' class='{$deleteClass}'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <?php if($expects):?>
      <div class='table-footer'>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
    <?php else:?>
    <div class='table-empty-tip'><?php echo $lang->noData;?></div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
