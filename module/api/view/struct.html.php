<?php
/**
 * The index view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Thanatos <thanatos915@163.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-row fade'>
  <div class='main-col'>
    <div class="cell" id="queryBox" data-module='user'></div>
    <div id='mainMenu' class='clearfix'>
      <div class='btn-toolbar pull-right'>
        <?php common::printLink('api', 'createStruct', "libID=$libID", "<i class='icon icon-plus'></i> " . $lang->api->createStruct, '', "class='btn btn-primary create-user-btn'");?>
      </div>
    </div>
    <?php if(empty($structs)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->api->noStruct;?></span>
      </p>
    </div>
    <?php else:?>
    <form class='main-table table-user' data-ride='table' method='post' data-checkable='false' id='userListForm'>
      <table class='table has-sort-head' id='userList'>
        <thead>
          <tr>
            <?php $vars = "libID={$libID}&releaseID=$releaseID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
            <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
            <th class='c-type'><?php common::printOrderLink('type', $orderBy, $vars, $lang->api->structType);?></th>
            <th class='c-name'><?php echo $lang->api->structName;?></th>
            <th class='c-user'><?php common::printOrderLink('addedBy', $orderBy, $vars, $lang->api->structAddedBy);?></th>
            <th class='c-date'><?php common::printOrderLink('addedDate', $orderBy, $vars, $lang->api->structAddedDate);?></th>
            <th class='c-actions'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($structs as $struct):?>
          <tr>
            <td><?php printf('%03d', $struct->id);?></td>
            <td><?php echo $struct->type;?></td>
            <td><?php echo $struct->name;?></td>
            <td><?php echo $struct->addedName;?></td>
            <td class="c-date"><?php echo $struct->addedDate;?></td>
            <td class='c-actions'>
              <?php
              if(common::hasPriv('api', 'editStruct')) echo html::a($this->createLink('api', 'editStruct', "libID=$libID&structID=$struct->id"), '<i class="icon-edit"></i>', '', "title='{$lang->api->editStruct}' class='btn'");
              if(common::hasPriv('api', 'deleteStruct')) echo html::a($this->createLink('api', 'deleteStruct', "libID=$libID&structID=$struct->id"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->api->deleteStruct}' class='btn'");
              ?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </form>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
