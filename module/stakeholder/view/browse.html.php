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
<?php js::set('confirmDelete', $lang->stakeholder->confirmDelete);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('stakeholder', 'browse', 'browseType=all'), '<span class="text">' . $lang->stakeholder->browse . '</span>', '', 'class="btn btn-link btn-active-text"');?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('stakeholder', 'batchcreate', "projectID=$projectID", "<i class='icon icon-plus'></i>" . $lang->stakeholder->batchCreate, '', "class='btn btn-primary'");?>
    <?php common::printLink('stakeholder', 'create', "projectID=$projectID", "<i class='icon icon-plus'></i>" . $lang->stakeholder->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id='mainContent' class='main-row fade'>
  <div class='main-col'>
    <?php if(!empty($stakeholders)):?>
    <form class='main-table table-user' data-ride='table' action='' method='post' id='userListForm'>
      <table class='table has-sort-head' id='userList'>
        <thead>
        <tr>
          <?php $vars = "projectID=$projectID&browseType=$browseType&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
          <th class='c-id'>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th><?php echo $lang->stakeholder->name;?></th>
          <th class="w-100px"><?php echo $lang->stakeholder->common . $lang->stakeholder->type;?></th>
          <th class="w-120px"><?php echo $lang->stakeholder->phone;?></th>
          <th class="w-120px"><?php echo $lang->stakeholder->qq;?></th>
          <th class="w-120px"><?php echo $lang->stakeholder->weixin;?></th>
          <th class="w-200px"><?php echo $lang->stakeholder->email;?></th>
          <th class='c-actions text-center w-150px'><?php echo $lang->actions;?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($stakeholders as $stakeholder):?>
        <tr>
          <td class='c-id'>
            <?php printf('%03d', $stakeholder->id);?>
          </td>
          <?php $stakeholder->name = $stakeholder->companyName ? $stakeholder->companyName . '/' . $stakeholder->name : $stakeholder->name;?>
          <?php $isKey = $stakeholder->key ? " <i class='icon icon-star-empty'></i>" : '';?>
          <?php $title = $stakeholder->key ? $stakeholder->name . '(' . $lang->stakeholder->isKey . ')' : $stakeholder->name;?>
          <td><?php common::printLink('stakeholder', 'view', "id=$stakeholder->id", $stakeholder->name . $isKey, '', "title=$title");?></td>
          <td title='<?php echo zget($lang->stakeholder->typeList, $stakeholder->type, '');?>'><?php echo zget($lang->stakeholder->typeList, $stakeholder->type, '');?></td>
          <td title="<?php echo $stakeholder->phone;?>"><?php echo $stakeholder->phone;?></td>
          <td title="<?php echo $stakeholder->qq;?>"><?php echo $stakeholder->qq;?></td>
          <td title="<?php echo $stakeholder->weixin;?>"><?php echo $stakeholder->weixin;?></td>
          <td title="<?php echo $stakeholder->email;?>"><?php echo $stakeholder->email;?></td>
          <td class='text-right c-actions'>
            <?php
            common::printIcon('stakeholder', 'communicate', "id=$stakeholder->id", $stakeholder, 'list', 'chat-line', '', 'iframe', 'yes');
            common::printIcon('stakeholder', 'expect', "id=$stakeholder->id", $stakeholder, 'list', 'flag', '', 'iframe', 'yes');
            if($stakeholder->projectModel == 'waterfall') common::printIcon('stakeholder', 'userIssue', "account=$stakeholder->id", $stakeholder, 'list', 'list-alt', '', 'iframe', 'yes');
            common::printIcon('stakeholder', 'edit', "id=$stakeholder->id", $stakeholder, 'list', '', '', '', '');
            $deleteClass = common::hasPriv('stakeholder', 'delete') ? 'btn' : 'btn disabled';
            echo html::a($this->createLink('stakeholder', 'delete', "id=$stakeholder->id"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->stakeholder->delete}' class='{$deleteClass}'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <?php if($stakeholders):?>
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
