<?php include $app->getModuleRoot() . 'common/view/header.html.php'?>
<?php js::set('mode', $mode);?>
<?php js::set('total', $pager->recTotal);?>
<?php js::set('rawMethod', $app->rawMethod);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->review->browseTypeList as $key => $type):?>
    <?php if(in_array($key, array('all', 'done', 'reviewing'))) continue;?>
    <?php if($app->rawMethod == 'work' && in_array($key, array('reviewedbyme', 'createdbyme', 'wait'))) continue;?>
    <?php if($app->rawMethod == 'contribute' && $key == 'wait') continue;?>
    <?php $active = $key == $browseType ? 'btn-active-text' : '';?>
    <?php $recTotalLabel = $key == $browseType ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>": '';?>
    <?php echo html::a($this->createLink('my', $app->rawMethod, "mode=$mode&browseType=$key"), '<span class="text">' . $type . '</span>' . $recTotalLabel, '', 'class="btn btn-link ' . $active .'"');?>
    <?php endforeach;?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class='main-col'>
    <?php if(empty($reviewList)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->noData;?></span>
      </p>
    </div>
    <?php else:?>
    <form class='main-table' method='post' id='myReviewForm'>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right"></nav>
      </div>
      <?php
      $vars = "mode=$mode&browseType=$browseType&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID";
      include $app->getModuleRoot() . 'common/view/datatable.html.php';

      $setting = $this->datatable->getSetting('review');
      foreach($setting as $key => $value)
      {
          if($value->id == 'actions') $setting[$key]->width = 80;
      }

      $widths  = $this->datatable->setFixedFieldWidth($setting);
      ?>
        <table class='table has-sort-head datatable' id='reviewList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>'>
          <thead>
            <tr>
              <?php
              foreach($setting as $value)
              {
                  if($value->show)
                  {
                      $this->datatable->printHead($value, $orderBy, $vars, false);
                  }
              }
              ?>
            </tr>
          </thead>
          <tbody>
          <?php foreach($reviewList as $review):?>
          <tr data-id='<?php echo $review->id?>'>
            <?php foreach($setting as $value) $this->my->reviewPrintCell($value, $review, $users, $products, $pendingList);?>
          </tr>
          <?php endforeach;?>
          </tbody>
        </table>
      <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    </form>
    <?php endif;?>
  </div>
</div>
<script>
$(function(){$('#myReviewForm').table();})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php'?>
