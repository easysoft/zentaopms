<?php include $app->getModuleRoot() . 'common/view/header.html.php'?>
<?php js::set('mode', $mode);?>
<?php js::set('total', $pager->recTotal);?>
<?php js::set('rawMethod', $app->rawMethod);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php $rawMethod = $app->rawMethod;?>
    <?php $menuKey   = $rawMethod . 'Menu';?>
    <?php foreach($lang->my->$menuKey->audit as $key => $type):?>
    <?php
    $active = $key == $browseType ? 'btn-active-text' : '';

    $recTotalLabel = '';
    if($key == $browseType) $recTotalLabel = " <span class='label label-light label-badge'>{$pager->recTotal}</span>";

    $param = "browseType=$key&param=&orderBy=time_desc";
    if($rawMethod == 'contribute') $param = "mode=$mode&browseType=$key&param=&orderBy=time_desc";
    ?>
    <?php echo html::a($this->createLink('my', $app->rawMethod, $param), '<span class="text">' . $type . '</span>' . $recTotalLabel, '', 'class="btn btn-link ' . $active .'"');?>
    <?php endforeach;?>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($reviewList)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->noData;?></span>
    </p>
  </div>
  <?php else:?>
  <form id='myReviewForm' class="main-table" method="post" data-ride="table">
  <table class='table has-sort-head' id='reviewList'>
    <thead>
      <?php
      $vars = "browseType=$browseType&param=&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID";
      if($rawMethod == 'contribute') $vars = "mode=$mode&browseType=$browseType&param=&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID";
      ?>
      <tr>
        <th class='c-id'>    <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
        <th class='c-title'> <?php $rawMethod == 'contribute' ? print($lang->my->audit->title) : common::printOrderLink('title', $orderBy, $vars, $lang->my->audit->title);?></th>
        <th class='c-type'>  <?php common::printOrderLink('type', $orderBy, $vars, $lang->my->audit->type);?></th>
        <th class='c-date w-150px'> <?php common::printOrderLink('time', $orderBy, $vars, $lang->my->audit->time);?></th>
        <th class='c-status w-80px'><?php $rawMethod == 'contribute' ? print($lang->my->audit->status) : common::printOrderLink('status', $orderBy, $vars, $lang->my->audit->status);?></th>
        <?php if($rawMethod == 'audit'):?>
        <th class='c-actions-2'><?php echo $lang->actions?></th>
        <?php endif;?>
      </tr>
    </thead>
    <tbody>
      <?php foreach($reviewList as $review):?>
      <?php
      $type     = $review->type;
      $typeName = $lang->$type->common;
      if($type == 'project') $type = 'review';

      $statusList = $lang->$type->statusList;
      if($type == 'attend') $statusList = $lang->attend->reviewStatusList;
      ?>
      <tr>
        <td class='c-id'><?php echo $review->id?></td>
        <td class='c-title' title='<?php echo $review->title?>'>
          <?php
          $titleHtml = $review->title;
          if(strpos(",{$config->my->oaObjectType},", ",$type,") === false) $titleHtml = html::a($this->createLink($type, 'view', "objectID=$review->id", 'html', true), $review->title, '', "class='iframe' data-width='90%'");
          echo $titleHtml;
          ?>
        </td>
        <td class='c-type'>  <?php echo $typeName;?></td>
        <td class='c-time'>  <?php echo $review->time?></td>
        <td class='c-status'><?php echo zget($statusList, $review->status, '')?></td>
        <?php if($rawMethod == 'audit'):?>
        <td class='c-actions'>
          <?php
          $module = $type;
          $method = 'review';
          $params = "id=$review->id";

          $reviewIcon = '<i class="icon-glasses"></i>';
          $passIcon   = '<i class="icon-check"></i>';
          $rejectIcon = '<i class="icon-close"></i>';

          if($module == 'review')
          {
              $method = 'assess';
              $params = "reviewID=$review->id&from={$rawMethod}";
              common::printLink($module, $method, $params, $reviewIcon, '', "class='btn' title='{$lang->review->common}'");
          }
          elseif($module == 'attend')
          {
              extCommonModel::printLink('attend', 'review', "attendID={$review->id}&status=pass",   $passIcon,   "class='btn' title='{$lang->attend->reviewStatusList['pass']}' data-status='pass' data-toggle='ajax'");
              extCommonModel::printLink('attend', 'review', "attendID={$review->id}&status=reject", $rejectIcon, "class='btn' title='{$lang->attend->reviewStatusList['reject']}' data-toggle='modal'");
          }
          elseif($module == 'leave')
          {
              $leaveMode = $review->status == 'pass' ? 'back' : '';
              extCommonModel::printLink('leave', 'review', "id={$review->id}&status=pass&mode=$leaveMode",   $passIcon,   "class='btn' title='{$lang->$module->statusList['pass']}' data-status='pass' data-toggle='ajax'");
              extCommonModel::printLink('leave', 'review', "id={$review->id}&status=reject&mode=$leaveMode", $rejectIcon, "class='btn' title='{$lang->$module->statusList['reject']}' data-toggle='modal'");
          }
          elseif(strpos('|makeup|overtime|lieu|', "|$module|") !== false)
          {
              extCommonModel::printLink($module, $method, "id={$review->id}&status=pass",   $passIcon,   "class='btn' title='{$lang->$module->statusList['pass']}' data-status='pass' data-toggle='ajax'");
              extCommonModel::printLink($module, $method, "id={$review->id}&status=reject", $rejectIcon, "class='btn' title='{$lang->$module->statusList['reject']}' data-toggle='modal'");
          }
          else
          {
              common::printLink($module, $method, $params, $reviewIcon, '', "class='btn iframe' title='{$lang->review->common}'", true, true);
          }
          ?>
        </td>
        <?php endif;?>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
  </form>
  <?php endif;?>
</div>
<?php js::set('confirmReview', $lang->my->confirmReview);?>
<script>
$(function()
{
    $('[data-toggle=ajax]').click(function()
    {
        if($(this).hasClass('disabled')) return false;
        var status = $(this).data('status');
        if(status == 'undefined' || confirm(confirmReview))
        {
            $.get($(this).prop('href'), function(response)
            {
                if(response.message) $.zui.messager.success(response.message);
                if(response.result == 'success') location.reload();
                return false;
            }, 'json');
        }
        return false;
    });
});
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php'?>
