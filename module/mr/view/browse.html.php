<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class='pull-right'>
    <?php if(common::hasPriv('repo', 'create')) echo html::a(helper::createLink('repo', 'create'), "<i class='icon icon-plus'></i> " . $lang->mr->add, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id='mainContent'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='gitlabProjectList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "objectID=$objectID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
          <th class='w-60px  text-left'><?php common::printOrderLink('id', $orderBy, $vars, $lang->repo->id); ?></th>
          <th class='w-120px'><?php common::printOrderLink('SCM', $orderBy, $vars, $lang->repo->type); ?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->repo->name); ?></th>
          <th class='w-400px text-left'><?php common::printOrderLink('product', $orderBy, $vars, $lang->repo->product); ?></th>
          <th class='w-100px c-actions-4'><?php echo $lang->actions; ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($repoList as $repo):?>
        <tr>
          <td class='text'><?php echo $repo->id; ?></td>
          <td class='text'><?php echo zget($lang->repo->scmList, $repo->SCM); ?></td>
          <td class='text' title='<?php echo $repo->name; ?>'><?php echo html::a($this->createLink('repo', 'browse', "repoID={$repo->id}&branchID=&objectID=$objectID"), $repo->name);?></td>
          <td class='text'>
          <?php
          $productList = explode(',', str_replace(' ', '', $repo->product));
          if(isset($productList) and $productList[0])
          {
              foreach($productList as $productID)
              {
                  if(!isset($products[$productID])) continue;
                  echo ' ' . html::a($this->createLink('product', 'browse', "productID=$productID"), zget($products, $productID, $productID));
              }
          }
          ?>
          </td>
          <td class='text-left c-actions'>
            <?php
            common::printLink('mr', 'list', "repo={$repo->id}", '<i class="icon icon-review"></i>', '', "title='{$lang->mr->list}' class='btn btn-info'");
            common::printLink('mr', 'create', "repo={$repo->id}", '<i class="icon icon-plus"></i>', '', "title='{$lang->mr->create}' class='btn btn-info'");
            common::printLink('repo', 'edit', "repoID=$repo->id&objectID=$objectID", '<i class="icon icon-edit"></i>', '', "title='{$lang->mr->edit}' class='btn btn-info'");
            if(common::hasPriv('repo', 'delete')) echo html::a($this->createLink('repo', 'delete', "repoID=$repo->id&objectID=$objectID"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->mr->delete}' class='btn'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($repoList):?>
    <div class='table-footer'><?php $pager->show('rignt', 'pagerjs');?></div>
    <?php endif;?>
  </form>
</div>
<?php include '../../common/view/footer.html.php'; ?>
