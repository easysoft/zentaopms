<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class='pull-right'>
<<<<<<< HEAD
    <?php if(common::hasPriv('mr', 'create')) echo html::a(helper::createLink('mr', 'create'), "<i class='icon icon-plus'></i> " . $lang->mr->addGitlab, '', "class='btn btn-primary'");?>
=======
    <?php if(common::hasPriv('mr', 'create')) echo html::a(helper::createLink('mr', 'create'), "<i class='icon icon-plus'></i> " . $lang->mr->create, '', "class='btn btn-primary'");?>
>>>>>>> 14de596bc0f1fc905a6c0cea6ac0eefd839e61fd
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
<<<<<<< HEAD
          <th class='w-100px text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->mr->name); ?></th>
          <th class='w-100px text-left'><?php common::printOrderLink('sourceBranch', $orderBy, $vars, $lang->mr->sourceBranch); ?></th>
          <th class='w-100px text-left'><?php common::printOrderLink('targetBranch', $orderBy, $vars, $lang->mr->targetBranch); ?></th>
          <th class='w-100px text-left'><?php common::printOrderLink('pipeline', $orderBy, $vars, $lang->mr->pipeline); ?></th>
          <th class='w-100px text-left'><?php common::printOrderLink('auditStatus', $orderBy, $vars, $lang->mr->auditStatus); ?></th>
          <th class='w-100px text-left'><?php common::printOrderLink('lastExec', $orderBy, $vars, $lang->mr->lastExec); ?></th>
=======
          <th class='w-200px text-left'><?php common::printOrderLink('project', $orderBy, $vars, $lang->mr->project); ?></th>
>>>>>>> 14de596bc0f1fc905a6c0cea6ac0eefd839e61fd
          <th class='w-100px c-actions-4'><?php echo $lang->actions; ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($mrList as $mr):?>
        <tr>
          <td class='text'><?php echo $mr->id; ?></td>
<<<<<<< HEAD
          <td class='text'><?php echo $mr->name; ?></td>
          <td class='text'><?php echo $mr->target_branch; ?></td>
          <td class='text'><?php echo $mr->source_branch; ?></td>
          <td class='text'><?php echo $mr->pipeline; ?></td>
          <td class='text'><?php echo $mr->auditStatus; ?></td>
          <td class='text'><?php echo $mr->lastExec; ?></td>
          <td class='text-left c-actions'>
            <?php
            common::printLink('mr', 'list', "mr={$mr->id}", '<i class="icon icon-review"></i>', '', "title='{$lang->mr->list}' class='btn btn-info'");
            common::printLink('mr', 'create', "mr={$mr->id}", '<i class="icon icon-plus"></i>', '', "title='{$lang->mr->create}' class='btn btn-info'");
            common::printLink('mr', 'edit', "mrID=$mr->id&objectID=$objectID", '<i class="icon icon-edit"></i>', '', "title='{$lang->mr->edit}' class='btn btn-info'");
            if(common::hasPriv('mr', 'delete')) echo html::a($this->createLink('mr', 'delete', "mrID=$mr->id&objectID=$objectID"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->mr->delete}' class='btn'");
            ?>
=======
          <td class='text' title='<?php echo $mr->projectID; ?>'><?php echo html::a($this->createLink('mr', 'browse', "mrID={$mr->id}&branchID=&objectID=$objectID"), $mr->projectID);?></td>
          <td class='text-left c-actions'>
          <?php
          common::printLink('mr', 'list', "mr={$mr->id}", '<i class="icon icon-review"></i>', '', "title='{$lang->mr->list}' class='btn btn-info'");
          common::printLink('mr', 'create', "mr={$mr->id}", '<i class="icon icon-plus"></i>', '', "title='{$lang->mr->create}' class='btn btn-info'");
          common::printLink('mr', 'edit', "mrID=$mr->id&objectID=$objectID", '<i class="icon icon-edit"></i>', '', "title='{$lang->mr->edit}' class='btn btn-info'");
          if(common::hasPriv('mr', 'delete')) echo html::a($this->createLink('mr', 'delete', "mrID=$mr->id&objectID=$objectID"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->mr->delete}' class='btn'");
          ?>
>>>>>>> 14de596bc0f1fc905a6c0cea6ac0eefd839e61fd
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
