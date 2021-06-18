<style>
.types-line {display: table; padding: 0; margin: 0 auto;}
.types-line > li {display: table-cell;}
.types-line > li > div {position: relative; padding: 18px 5px 5px; text-align: center;}
.types-line > li > div:before {content: ''; display: block; background: #eee;  width: 100%; height: 2px; position: absolute; left: 50%; top: 4px;}
.types-line > li:last-child > div:before {display: none;}
.types-line > li > div:after {content: ''; display: block; background: #FFFFFF; box-shadow: 0 2px 4px 0 rgba(170,170,170,0.50), 0 0 5px 0 rgba(0,0,0,0.1); width: 10px; height: 10px; position: absolute; border-radius: 50%; top: 0; left: 50%; margin-left: -2px;}
.types-line > li > div > small {display: block; color: #A6AAB8;}
.types-line > li > div > span {display: block; color: #CBD0DB; font-size: 16px;}
.product-info {position: relative; height: 65px;}
.product-info + .product-info {margin-top: 10px;}
.product-info .progress {position: absolute; left: 10px; top: 35px; right: 100px;}
.product-info .progress-info {position: absolute; left: 8px; top: 10px; width: 180px; font-size: 12px;}
.product-info .type-info {color: #A6AAB8; text-align: center; position: absolute; right: 0; top: 6px; width: 100px;}
html[lang="en"] .product-info .type-info {color: #A6AAB8; text-align: center; position: absolute; right: 0; top: 6px; width: 110px;}
.product-info .type-value,
.product-info .type-label {font-size: 12px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
.product-info .type-value {font-size: 14px;}
.product-info .type-value > strong {font-size: 20px; color: #3C4353;}
.product-info .actions {position: absolute; left: 10px; top: 14px;}
.block-statistic .panel-body {padding-top: 0}
.block-statistic .tile {margin-bottom: 30px;}
.block-statistic .tile-title {font-size: 18px; color: #A6AAB8;}
.block-statistic .tile-amount {font-size: 48px; margin-bottom: 10px;}
.block-statistic .col-nav {border-right: 1px solid #EBF2FB; width: 260px; padding: 0;}
.block-statistic .nav-secondary > li {position: relative}
.block-statistic .nav-secondary > li > a {font-size: 14px; color: #838A9D; position: relative; box-shadow: none; padding-left: 20px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; transition: all .2s;}
.block-statistic .nav-secondary > li > a:first-child {padding-right: 36px;}
.block-statistic .nav-secondary > li.active > a:first-child {color: #3C4353; background: transparent; box-shadow: none;}
.block-statistic .nav-secondary > li.active > a:first-child:hover,
.block-statistic .nav-secondary > li.active > a:first-child:focus,
.block-statistic .nav-secondary > li > a:first-child:hover {box-shadow: none; border-radius: 4px 0 0 4px;}
.block-statistic .nav-secondary > li.active > a:first-child:before {content: ' '; display: block; left: -1px; top: 10px; bottom: 10px; width: 4px; background: #006af1; position: absolute;}
.block-statistic .nav-secondary > li > a.btn-view {position: absolute; top: 0; right: 0; bottom: 0; padding: 8px; width: 36px; text-align: center; opacity: 0;}
.block-statistic .nav-secondary > li:hover > a.btn-view {opacity: 1}
.block-statistic .nav-secondary > li.active > a.btn-view {box-shadow: none}
.block-statistic .nav-secondary > li.switch-icon {display: none;}
.block-statistic.block-sm .panel-body {padding-bottom: 10px; position: relative; padding-top: 45px; border-radius: 3px;}
.block-statistic.block-sm .panel-body > .table-row,
.block-statistic.block-sm .panel-body > .table-row > .col {display: block; width: auto;}
.block-statistic.block-sm .panel-body > .table-row > .tab-content {padding: 0; margin: 0 -5px;}
.block-statistic.block-sm .tab-pane > .table-row > .col-5 {width: 125px;}
.block-statistic.block-sm .tab-pane > .table-row > .col-5 > .table-row {padding: 5px 0;}
.block-statistic.block-sm .col-nav {border-left: none; position: absolute; top: 0; left: 15px; right: 15px; background: #f5f5f5;}
.block-statistic.block-sm .nav-secondary {display: table; width: 100%; padding: 0; table-layout: fixed;}
.block-statistic.block-sm .nav-secondary > li {display: none}
.block-statistic.block-sm .nav-secondary > li.switch-icon,
.block-statistic.block-sm .nav-secondary > li.active {display: table-cell; width: 100%; text-align: center;}
.block-statistic.block-sm .nav-secondary > li.active > a:hover {cursor: default; background: none;}
.block-statistic.block-sm .nav-secondary > li.switch-icon > a:hover {background: rgba(0,0,0,0.07);}
.block-statistic.block-sm .nav-secondary > li > a {padding: 5px 10px; border-radius: 4px;}
.block-statistic.block-sm .nav-secondary > li > a:before {display: none;}
.block-statistic.block-sm .nav-secondary > li.switch-icon {width: 40px;}
.block-statistic.block-sm .nav-secondary > li.active > a:first-child:before {display: none}
.block-statistic.block-sm .nav-secondary > li.active > a.btn-view {width: auto; left: 0; right: 0;}
.block-statistic.block-sm .nav-secondary > li.active > a.btn-view > i {display: none;}
.block-statistic.block-sm .nav-secondary > li.active > a.btn-view:hover {cursor: pointer; background: rgba(0,0,0,.1)}

.status-count{margin:auto}
.status-count tr:first-child td:last-child{color:#000;font-weight:bold}
</style>
<script>
<?php $blockNavId = 'nav-' . uniqid(); ?>
$(function()
{
    var $nav = $('#<?php echo $blockNavId;?>');
    $nav.on('click', '.switch-icon', function(e)
    {
        var isPrev = $(this).is('.prev');
        var $activeItem = $nav.children('.active');
        var $next = $activeItem[isPrev ? 'prev' : 'next']('li:not(.switch-icon)');
        if ($next.length) $next.find('a').trigger('click');
        else $nav.children('li:not(.switch-icon)')[isPrev ? 'last' : 'first']().find('a').trigger('click');
        e.preventDefault();
    });

    var $executionLi = $('#activeExecution');
    if($executionLi.length)
    {
        var executionLi  = $executionLi[0];
        $(".col ul.nav").animate({scrollTop: executionLi.offsetTop}, "slow");
    }
});
</script>
<div class="panel-body">
  <div class="table-row">
    <?php if(empty($executions)):?>
    <div class="table-empty-tip">
      <p><span class="text-muted"><?php echo $lang->block->noData;?></span></p>
    </div>
    <?php else:?>
    <div class="col col-nav">
      <ul class="nav nav-stacked nav-secondary scrollbar-hover" id='<?php echo $blockNavId;?>'>
        <li class='switch-icon prev'><a><i class='icon icon-arrow-left'></i></a></li>
        <?php $selected = key($executions);?>
        <?php $selected = !isset($executions[$selected]) ? key($executions) : $selected;?>
        <?php foreach($executions as $execution):?>
        <li <?php if($execution->id == $selected) echo "class='active' id='activeExecution'";?> executionID='<?php echo $execution->id;?>'>
          <a href="###" data-target="#tab3Content<?php echo $execution->id;?>" data-toggle="tab" title='<?php echo $execution->name;?>'><?php echo $execution->name;?></a>
          <?php echo html::a(helper::createLink('execution', 'task', "executionID=$execution->id"), "<i class='icon-arrow-right text-primary'></i>", '', "class='btn-view' title={$lang->execution->task}");?>
        </li>
        <?php endforeach;?>
        <li class='switch-icon next'><a><i class='icon icon-arrow-right'></i></a></li>
      </ul>
    </div>
    <div class="col tab-content">
      <?php foreach($executions as $execution):?>
      <div class="tab-pane fade<?php if($execution->id == $selected) echo ' active in';?>" id="tab3Content<?php echo $execution->id;?>">
        <div class="table-row">
          <div class="col-5 text-middle text-center">
            <div class="progress-pie inline-block space" data-value="<?php echo $execution->progress;?>" data-doughnut-size="84">
              <canvas width="120" height="120"></canvas>
              <div class="progress-info">
                <small><?php echo $lang->task->statusList['done'];?></small>
                <strong><?php echo $execution->progress;?><small><?php echo $lang->percent;?></small></strong>
              </div>
            </div>
            <div class="table-row text-center small text-muted with-padding">
              <div class="col-4 text-bottom">
                <div><?php echo $lang->execution->totalEstimate;?></div>
                <div><?php echo (float)$execution->totalEstimate;?> <span class="muted"><?php echo $lang->task->hour;?></span></div>
              </div>
              <div class="col-4">
                <span class="label label-dot label-primary"></span>
                <div><?php echo $lang->execution->totalConsumed;?></div>
                <div><?php echo (float)$execution->totalConsumed;?> <span class="muted"><?php echo $lang->task->hour;?></span></div>
              </div>
              <div class="col-4">
                <span class="label label-dot label-pale"></span>
                <div><?php echo $lang->execution->totalLeft;?></div>
                <div><?php echo (float)$execution->totalLeft;?> <span class="muted"><?php echo $lang->task->hour;?></span></div>
              </div>
            </div>
          </div>
          <div class="col-7">
            <div class="product-info">
              <div class="progress-info"><i class="icon icon-check-circle text-success icon-sm"></i> <span class="text-muted"><?php echo $lang->task->yesterdayFinished;?></span> <strong><?php echo $execution->yesterdayFinished;?></strong></div>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $execution->taskProgress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $execution->taskProgress;?>%">
                </div>
              </div>
              <div class="type-info">
                <div class="type-label">
                  <table class='status-count'>
                    <tr>
                      <td class='text-right'><?php echo $lang->task->allTasks;?> :</td>
                      <td class='text-left'><?php echo empty($execution->totalTasks) ? 0 : html::a($this->createLink('execution', 'task', "executionID={$execution->id}&status=all"), $execution->totalTasks);?></td>
                    </tr>
                    <tr>
                      <td class='text-right'><?php echo $lang->task->noFinished;?> :</td>
                      <td class='text-left'><?php echo empty($execution->undoneTasks) ? 0 : html::a($this->createLink('execution', 'task', "executionID={$execution->id}&status=undone"), $execution->undoneTasks);?></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="product-info">
              <div class="progress-info"><i class="icon icon-check-circle text-success icon-sm"></i> <span class="text-muted"><?php echo $lang->story->released;?></span> <strong><?php echo $execution->releasedStories;?></strong></div>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $execution->storyProgress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $execution->storyProgress;?>%"></div>
              </div>
              <div class="type-info">
                <div class="type-label">
                  <table class='status-count'>
                    <tr>
                      <td class='text-right'><?php echo $lang->story->total;?> :</td>
                      <td class='text-left'><?php echo empty($execution->totalStories) ? 0 : html::a($this->createLink('execution', 'story', "executionID={$execution->id}&orderBy=order_desc&type=all"), $execution->totalStories);?></td>
                    </tr>
                    <tr>
                      <td class='text-right'><?php echo $lang->story->unclosed;?> :</td>
                      <td class='text-left'><?php echo empty($execution->unclosedStories) ? 0 : html::a($this->createLink('execution', 'story', "executionID={$execution->id}&orderBy=order_desc&type=unclosed"), $execution->unclosedStories);?></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="product-info">
              <div class="progress-info"><i class="icon icon-check-circle text-success icon-sm"></i> <span class="text-muted"><?php echo $lang->bug->yesterdayResolved;?></span> <strong><?php echo $execution->yesterdayResolved;?></strong></div>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $execution->bugProgress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $execution->bugProgress;?>%">
                </div>
              </div>
              <div class="type-info">
                <div class="type-label">
                  <table class='status-count'>
                    <tr>
                      <td class='text-right'><?php echo $lang->bug->allBugs;?> :</td>
                      <td class='text-left'><?php echo empty($execution->totalBugs) ? 0 : html::a($this->createLink('execution', 'bug', "executionID={$execution->id}&orderBy=status,id_desc&build=0&type=all"), $execution->totalBugs);?></td>
                    </tr>
                    <tr>
                      <td class='text-right'><?php echo $lang->bug->unResolved;?> :</td>
                      <td class='text-left'><?php echo empty($execution->activeBugs) ? 0 : html::a($this->createLink('execution', 'bug', "executionID={$execution->id}&orderBy=status,id_desc&build=0&type=unresolved"), $execution->activeBugs);?></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach;?>
    </div>
    <?php endif;?>
  </div>
</div>
