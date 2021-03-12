<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::canModify('execution', $execution)) echo html::a(helper::createLink('testcase', 'create', "productID=$productID&branch=0&extra=executionID=$execution->id", '', '', '', true), "<i class='icon icon-plus'></i> " . $lang->testcase->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($cases)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->testcase->noCase;?></span>
      <?php if(common::canModify('execution', $execution) and common::hasPriv('testcase', 'create')):?>
      <?php echo html::a(helper::createLink('testcase', 'create', "productID=$productID&branch=0&extra=executionID=$execution->id", '', '', '', true), "<i class='icon icon-plus'></i> " . $lang->testcase->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table' method='post' id='executionBugForm' data-ride="table">
    <table class='table has-sort-head' id='bugList'>
      <thead>
        <tr>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
