<?php
/**
 * The action->dynamic view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: action->dynamic.html.php 1477 2011-03-01 15:25:50Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('browseType', $browseType);?>
<?php js::set('systemMode', $config->systemMode);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->action->periods as $period => $label):?>
    <?php
    $label  = "<span class='text'>$label</span>";
    $active = '';
    if($period == $browseType)
    {
        $active = 'btn-active-text';
        $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
    }
    echo html::a(inlink('dynamic', "browseType=$period&param=&recTotal=0&date=&direction=next&userID=$userID&product=$product&project=$project&execution=$execution"), $label, '', "class='btn btn-link $active' id='{$period}'")
    ?>
    <?php endforeach;?>
    <div class="input-control space c-user"><?php echo html::select('account', $userIdPairs, $userID, 'class="form-control chosen" data-max_drop_width="215"');?></div>
    <div class="input-control space c-product"><?php echo html::select('product', $products, $product, 'class="form-control chosen" data-max_drop_width="215"');?></div>
    <?php if($config->systemMode == 'new'):?>
    <div class="input-control space c-project"><?php echo html::select('project', $projects, $project, 'class="form-control chosen" data-max_drop_width="215"');?></div>
    <?php endif;?>
    <div class="input-control space c-execution"><?php echo html::select('execution', $executions, $execution, 'class="form-control chosen" data-max_drop_width="215"'); ?></div>
    <a class="btn btn-link querybox-toggle" id="bysearchTab"><i class="icon icon-search muted"></i> <?php echo $lang->action->dynamic->search;?></a>
  </div>
</div>

<div id='queryBox' data-module='action' class='cell <?php if($browseType =='bysearch') echo 'show';?>'></div>
<div id='mainContent' class='main-content'>
  <div id='dynamics'>
    <?php $firstAction = '';?>
    <?php foreach($dateGroups as $date => $actions):?>
    <?php $isToday = date(DT_DATE4) == $date;?>
    <div class="dynamic <?php if($isToday) echo 'active';?>">
      <div class="dynamic-date <?php if($browseType == 'all') echo 'c-date';?>">
        <?php if($isToday):?>
        <span class="date-label"><?php echo $lang->action->dynamic->today;?></span>
        <?php endif;?>
        <span class="date-text"><?php echo $date;?></span>
        <button type="button" class="btn btn-info btn-icon btn-sm dynamic-btn"><i class="icon icon-caret-down"></i></button>
      </div>
      <ul class="timeline timeline-tag-left <?php if($browseType == 'all') echo 'margin-l-50px';?>">
        <?php if($direction == 'next') $actions = array_reverse($actions);?>
        <?php foreach($actions as $i => $action):?>
        <?php if(empty($firstAction)) $firstAction = $action;?>
        <li <?php if($action->major) echo "class='active'";?>>
          <div>
            <span class="timeline-tag"><?php echo $action->time?></span>
            <span class="timeline-text">
              <?php echo zget($accountPairs, $action->actor);?>
              <span class='label-action'><?php echo ' ' . $action->actionLabel;?></span>
              <?php if($action->action != 'login' and $action->action != 'logout'):?>
              <span class="text"><?php echo $action->objectLabel;?></span>
              <?php $tab = '';?>
              <?php if($action->objectType == 'meeting') $tab = $action->project ? "data-app='project'" : "data-app='my'";?>
              <?php
              if((isset($config->maxVersion) and strpos(",{$config->action->assetType},", ",{$action->objectType},") !== false) or empty($action->objectName))
              {
                  echo '#' . $action->objectID;
              }
              elseif(empty($action->objectLink))
              {
                  echo $action->objectName;
              }
              else
              {
                  echo html::a($action->objectLink, $action->objectName, '', $tab);
              }
              ?>
              <span class="label label-id"><?php echo $action->objectID;?></span>
              <?php endif;?>
            </span>
          </div>
        </li>
        <?php endforeach;?>
      </ul>
    </div>
    <?php endforeach;?>
  </div>
</div>
<?php if(!empty($firstAction)):?>
<?php
$firstDate = date('Y-m-d', strtotime($firstAction->originalDate) + 24 * 3600);
$lastDate  = substr($action->originalDate, 0, 10);
$hasPre    = $this->action->hasPreOrNext($firstDate, 'pre');
$hasNext   = $this->action->hasPreOrNext($lastDate, 'next');
$preLink   = $hasPre ? inlink('dynamic', "browseType=$browseType&param=$param&recTotal={$pager->recTotal}&date=" . strtotime($firstDate) . '&direction=pre') : 'javascript:;';
$nextLink  = $hasNext ? inlink('dynamic', "browseType=$browseType&param=$param&recTotal={$pager->recTotal}&date=" . strtotime($lastDate) . '&direction=next') : 'javascript:;';
?>
<?php if($hasPre or $hasNext):?>
<div id="mainActions" class='main-actions'>
  <nav class="container">
    <a id="prevPage" class="btn btn-info<?php if(!$hasNext) echo ' disabled';?>" href="<?php echo $nextLink;?>"><i class="icon icon-chevron-left"></i></a>
    <a id="nextPage" class="btn btn-info<?php if(!$hasPre) echo ' disabled';?>" href="<?php echo $preLink;?>"><i class="icon icon-chevron-right"></i></a>
  </nav>
</div>
<?php endif;?>
<?php endif;?>
<script>
var browseType = '<?php echo $browseType;?>';
$(function()
{
    $('#dynamics').on('click', '.dynamic-btn', function()
    {
        $(this).closest('.dynamic').toggleClass('collapsed');
    });
})
</script>
<?php include '../../common/view/footer.html.php';?>
