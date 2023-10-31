<?php include $app->getModuleRoot() . 'common/view/header.html.php'?>
<?php js::set('mode', $mode);?>
<?php js::set('total', $pager->recTotal);?>
<?php js::set('rawMethod', $app->rawMethod);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php $rawMethod = $app->rawMethod;?>
    <?php $menuLang  = $rawMethod == 'audit' ? $lang->my->featureBar['audit'] : $lang->my->featureBar[$rawMethod]['audit'];?>
    <?php foreach($menuLang as $key => $type):?>
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
    <thead class='text-center'>
      <?php
      $vars = "browseType=$browseType&param=&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID";
      if($rawMethod == 'contribute') $vars = "mode=$mode&browseType=$browseType&param=&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID";
      ?>
      <tr>
        <th class='c-id'>             <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
        <th class='c-title text-left'><?php $rawMethod == 'contribute' ? print($lang->my->auditField->title) : common::printOrderLink('title', $orderBy, $vars, $lang->my->auditField->title);?></th>
        <th class='c-type w-120px'>   <?php common::printOrderLink('type', $orderBy, $vars, $lang->my->auditField->type);?></th>
        <th class='c-date w-150px'>   <?php common::printOrderLink('time', $orderBy, $vars, $lang->my->auditField->time);?></th>
        <?php if($rawMethod == 'contribute' and $browseType == 'reviewedbyme'):?>
        <th class='c-status w-150px'> <?php print($lang->my->auditField->result);?></th>
        <?php endif;?>
        <th class='c-status w-110px'> <?php $rawMethod == 'contribute' ? print($lang->my->auditField->status) : common::printOrderLink('status', $orderBy, $vars, $lang->my->auditField->status);?></th>
        <?php if($rawMethod == 'audit'):?>
        <th class='c-actions-1'><?php echo $lang->actions?></th>
        <?php endif;?>
      </tr>
    </thead>
    <tbody class='text-center'>
      <?php foreach($reviewList as $review):?>
      <?php
      $type = $review->type;
      if($type == 'projectreview') $type = 'review';

      $typeName = '';
      if(isset($lang->{$review->type}->common)) $typeName = $lang->{$review->type}->common;
      if($type == 'story') $typeName = $review->storyType == 'story' ? $lang->SRCommon : $lang->URCommon;
      if($review->type == 'projectreview') $typeName = $lang->project->common;
      if(isset($flows[$review->type])) $typeName = $flows[$review->type];

      $statusList = array();
      if(isset($lang->$type->statusList)) $statusList = $lang->$type->statusList;
      if($type == 'attend') $statusList = $lang->attend->reviewStatusList;
      if(!in_array($type, array('demand', 'story', 'testcase', 'feedback', 'review')) and strpos(",{$config->my->oaObjectType},", ",$type,") === false)
      {
          if($rawMethod == 'audit') $statusList = $lang->approval->nodeList;
          if(isset($flows[$review->type])) $statusList = $rawMethod == 'audit' ? $lang->approval->nodeList : $lang->approval->statusList;
      }
      ?>
      <tr>
        <td class='c-id'><?php echo $review->id?></td>
        <td class='c-title text-left' title='<?php echo $review->title?>'>
          <?php
          $titleHtml = $review->title;
          if($type == 'attend')
          {
              $titleHtml = html::a($this->createLink($type, 'review', "objectID=$review->id", 'html', true), $review->title, '', "data-toggle='modal'");
          }
          else
          {
              $class = "class='iframe' data-width='90%'";
              if(strpos(",{$config->my->oaObjectType},", ",{$type},") !== false) $class = "data-toggle='modal'";
              $titleHtml = html::a($this->createLink($type, 'view', "objectID=$review->id", 'html', true), $review->title, '', $class);
          }
          echo $titleHtml;
          ?>
        </td>
        <td class='c-type'><?php echo $typeName;?></td>
        <td class='c-time text-left'><?php echo substr($review->time, 0, 19);?></td>
        <?php if($rawMethod == 'contribute' and $browseType == 'reviewedbyme'):?>
        <?php
        $reviewResultList = array();
        if(isset($lang->$type))$reviewResultList = zget($lang->$type, 'reviewResultList', array());
        if(strpos(",{$config->my->oaObjectType},", ",$type,") !== false) $reviewResultList = zget($lang->$type, 'reviewStatusList', array());
        ?>
        <td class='c-status'><?php echo zget($reviewResultList, $review->result);?></td>
        <?php endif;?>
        <td class='c-status'><?php echo zget($statusList, $review->status, '');?></td>
        <?php if($rawMethod == 'audit'):?>
        <td class='c-actions text-left'>
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
              common::printLink($module, 'review', "attendID={$review->id}&status=", $reviewIcon, '', "class='btn' data-toggle='modal' title='{$lang->review->common}'", true, true);
          }
          elseif(strpos(",{$config->my->oaObjectType},", ",$module,") !== false)
          {
              common::printLink($module, 'view', $params, $reviewIcon, '', "class='btn' data-toggle='modal' title='{$lang->review->common}'", true, true);
          }
          elseif(!in_array($module, array('demand', 'story', 'testcase', 'feedback')))
          {
              common::printLink($module, 'approvalreview', $params, $reviewIcon, '', "class='btn' data-toggle='modal' title='{$lang->review->common}'", true, true);
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
        if(status == 'undefined' || confirm(confirmReview.pass))
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
