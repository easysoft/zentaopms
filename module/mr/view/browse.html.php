<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class='pull-right'>
    <?php if(common::hasPriv('mr', 'create')) echo html::a(helper::createLink('mr', 'create'), "<i class='icon icon-plus'></i> " . $lang->mr->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id='mainContent'>
<?php if(empty($mrList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData . $lang->mr->common;?></span>
    <?php if(common::hasPriv('mr', 'create')):?>
    <?php echo html::a($this->createLink('mr', 'create'), "<i class='icon icon-plus'></i> " . $lang->mr->create, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else: ?>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='gitlabProjectList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "objectID=$objectID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
          <th class='w-60px  text-left'><?php common::printOrderLink('id', $orderBy, $vars, $lang->mr->id); ?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('project', $orderBy, $vars, $lang->mr->project); ?></th>
          <th class='w-100px c-actions-4'><?php echo $lang->actions; ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($mrList as $mr):?>
        <tr>
          <td class='text'><?php echo $mr->id; ?></td>
          <td class='text' title='<?php echo $mr->projectID; ?>'><?php echo html::a($this->createLink('mr', 'browse', "mrID={$mr->id}&branchID=&objectID=$objectID"), $mr->projectID);?></td>
          <td class='text-left c-actions'>
          <?php
          common::printLink('mr', 'list', "mr={$mr->id}", '<i class="icon icon-review"></i>', '', "title='{$lang->mr->list}' class='btn btn-info'");
          common::printLink('mr', 'create', "mr={$mr->id}", '<i class="icon icon-plus"></i>', '', "title='{$lang->mr->create}' class='btn btn-info'");
          common::printLink('mr', 'edit', "mrID=$mr->id&objectID=$objectID", '<i class="icon icon-edit"></i>', '', "title='{$lang->mr->edit}' class='btn btn-info'");
          if(common::hasPriv('mr', 'delete')) echo html::a($this->createLink('mr', 'delete', "mrID=$mr->id&objectID=$objectID"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->mr->delete}' class='btn'");
          ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($mrList):?>
    <div class='table-footer'><?php $pager->show('rignt', 'pagerjs');?></div>
    <?php endif;?>
  </form>
<?php endif;?>
</div>
<?php include '../../common/view/footer.html.php'; ?>
