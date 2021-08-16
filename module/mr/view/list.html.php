<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class='pull-right'>
    <?php if(common::hasPriv('mr', 'create')) echo html::a(helper::createLink('mr', 'create'), "<i class='icon icon-plus'></i> " . $lang->mr->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id='mainContent'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='mrList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
          <th class='w-60px  text-left'><?php common::printOrderLink('id', $orderBy, $vars, $lang->mr->id); ?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->mr->name); ?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->mr->sourceProject); ?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->mr->targetProject); ?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('status', $orderBy, $vars, $lang->mr->status); ?></th>
          <th class='w-100px c-actions-4'><?php echo $lang->actions; ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($mrList as $mr):?>
        <tr>
          <td class='text'><?php echo $mr->id; ?></td>
          <td class='text'><?php echo html::a($this->createLink('mr', 'browse', "mrID={$mr->id}"), $mr->title);?></td>
          <td class='text'><?php echo $mr->source_project_id . ":" . $mr->source_branch;?></td>
          <td class='text'><?php echo $mr->target_project_id . ":" . $mr->target_branch;?></td>
          <td class='text'><?php echo $mr->state;?></td>
          <td class='text-left c-actions'>
            <?php
            common::printLink('mr', 'list', "mr={$mr->id}", '<i class="icon icon-review"></i>', '', "title='{$lang->mr->list}' class='btn btn-info'");
            common::printLink('mr', 'create', "mr={$mr->id}", '<i class="icon icon-plus"></i>', '', "title='{$lang->mr->create}' class='btn btn-info'");
            common::printLink('mr', 'edit', "mrID=$mr->id", '<i class="icon icon-edit"></i>', '', "title='{$lang->mr->edit}' class='btn btn-info'");
            if(common::hasPriv('mr', 'delete')) echo html::a($this->createLink('mr', 'delete', "mrID=$mr->id"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->mr->delete}' class='btn'");
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
</div>
<?php include '../../common/view/footer.html.php'; ?>
