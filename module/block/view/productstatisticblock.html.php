<?php
/**
 * The report block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
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
.product-info .progress {position: absolute; left: 10px; top: 35px; right: 90px;}
.product-info .progress-info {position: absolute; left: 8px; top: 10px; width: 180px; font-size: 12px;}
.product-info .type-info {color: #A6AAB8; text-align: center; position: absolute; right: 0; top: 6px; width: 100px;}
html[lang="en"] .product-info .type-info {color: #A6AAB8; text-align: center; position: absolute; right: 0; top: 6px; width: 90px;}
.product-info .type-value,
.product-info .type-label {font-size: 12px; overflow: visible; text-overflow: ellipsis; white-space: nowrap;}
.product-info .type-value {font-size: 14px;}
.product-info .type-value > strong {font-size: 20px; color: #3C4353;}
.product-info .actions {position: absolute; left: 10px; top: 14px;}
.block-statistic .panel-body {padding-top: 0}
.block-statistic .panel-body {padding-top: 0}
.block-statistic .tile {margin-bottom: 30px;}
.block-statistic .tile-title {font-size: 18px; color: #A6AAB8;}
.block-statistic .tile-amount {font-size: 48px; margin-bottom: 10px;}
.block-statistic .col-nav {border-right: 1px solid #EBF2FB; width: 260px; padding: 0;}
.block-statistic .nav-secondary > li {position: relative}
.block-statistic .nav-secondary > li:hover {background: #f5f5f5;}
.block-statistic .nav-secondary > li > a {font-size: 14px; color: #838A9D; position: relative; box-shadow: none; padding-left: 20px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; transition: all .2s;}
.block-statistic .nav-secondary > li > a:first-child {padding-right: 36px;}
.block-statistic .nav-secondary > li.active > a:first-child {color: #3C4353; background: transparent; box-shadow: none;}
.block-statistic .nav-secondary > li.active > a:first-child:hover,
.block-statistic .nav-secondary > li.active > a:first-child:focus,
.block-statistic .nav-secondary > li > a:first-child:hover {box-shadow: none; border-radius: 4px 0 0 4px;}
.block-statistic .nav-secondary > li.active > a:first-child:before {content: ' '; display: block; left: -1px; top: 10px; bottom: 10px; width: 4px; background: #006af1; position: absolute;}
.block-statistic .nav-secondary > li > a.btn-view {position: absolute; top: 0; right: 0; bottom: 0; padding: 8px; width: 36px; text-align: center; opacity: 0; background: rgba(0,0,0,.05)}
.block-statistic .nav-secondary > li.active > a.btn-view {box-shadow: none}
.block-statistic .nav-secondary > li:hover > a.btn-view {opacity: 1}
.block-statistic .nav-secondary > li.switch-icon {display: none;}
.block-statistic.block-sm .panel-body {padding-bottom: 10px; position: relative; padding-top: 45px;}
.block-statistic.block-sm .panel-body > .table-row,
.block-statistic.block-sm .panel-body > .table-row > .col {display: block; width: auto;}
.block-statistic.block-sm .panel-body > .table-row > .tab-content {padding: 0; margin: 0 -5px;}
.block-statistic.block-sm .tab-pane > .table-row > .col-5 {width: 125px;}
.block-statistic.block-sm .tab-pane > .table-row > .col-5 > .table-row {padding: 5px 0;}
.block-statistic.block-sm .col-nav {border-left: none; position: absolute; top: 0; left: 15px; right: 15px; background: #f5f5f5; border-radius: 3px;}
.block-statistic.block-sm .nav-secondary {display: table; width: 100%; padding: 0; table-layout: fixed;}
.block-statistic.block-sm .nav-secondary > li {display: none}
.block-statistic.block-sm .nav-secondary > li.switch-icon,
.block-statistic.block-sm .nav-secondary > li.active {display: table-cell; width: 100%; text-align: center;}
.block-statistic.block-sm .nav-secondary > li.active > a:hover {cursor: default; background: none;}
.block-statistic.block-sm .nav-secondary > li.switch-icon > a:hover {background: rgba(0,0,0,0.07);}
.block-statistic.block-sm .nav-secondary > li > a {padding: 5px 10px; border-radius: 4px;}
.block-statistic.block-sm .nav-secondary > li > a:before {display: none;}
.block-statistic.block-sm .nav-secondary > li.switch-icon {width: 40px;}
.block-statistic.block-sm .types-line > li > div {padding: 18px 2px 5px;}
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

    var $productLi = $('#activeProduct');
    if($productLi.length)
    {
        var productLi  = $productLi[0];
        $(".col ul.nav").animate({scrollTop: productLi.offsetTop}, "slow");
    }
});
</script>
<div class="panel-body">
  <div class="table-row">
    <?php if(empty($products)):?>
    <div class="table-empty-tip">
      <p><span class="text-muted"><?php echo $lang->block->noData;?></span></p>
    </div>
    <?php else:?>
    <div class="col col-nav">
      <ul class="nav nav-stacked nav-secondary scrollbar-hover" id='<?php echo $blockNavId;?>'>
        <li class='switch-icon prev'><a><i class='icon icon-arrow-left'></i></a></li>
        <?php $selected = empty($_SESSION['product'])  ? key($products) : $this->session->product;?>
        <?php $selected = !isset($products[$selected]) ? key($products) : $selected;?>
        <?php foreach($products as $product):?>
        <li <?php if($product->id == $selected) echo "class='active' id='activeProduct'";?> productID='<?php echo $product->id;?>'>
          <a href="javascript:;" data-target="#tabProduct<?php echo $product->id;?>" data-toggle="tab" title='<?php echo $product->name;?>'><?php echo $product->name;?></a>
          <?php echo html::a(helper::createLink('product', 'browse', "productID=$product->id"), "<i class='icon-arrow-right text-primary'></i>", '', "class='btn-view' title={$lang->product->browse}");?></li>
        <?php endforeach;?>
        <li class='switch-icon next'><a><i class='icon icon-arrow-right'></i></a></li>
      </ul>
    </div>
    <div class="col tab-content">
      <?php foreach($products as $product):?>
      <div class="tab-pane fade <?php if($product->id == $selected) echo 'active in';?>" id="tabProduct<?php echo $product->id;?>">
        <div class="table-row">
          <div class="col-6 text-middle">
            <div class="tile">
              <div class="tile-title"><?php echo $lang->story->total;?></div>
              <?php if($product->stories):?>
              <div class="tile-amount"><?php echo array_sum($product->stories);?></div>
              <?php common::printLink('product', 'browse', "productID={$product->id}&branch=&type=allstory", $lang->story->viewAll . '<span class="label label-badge label-icon"><i class="icon icon-arrow-right"></i></span>', '', 'class="btn btn-primary btn-circle btn-icon-right btn-sm"');?>
              <?php else:?>
              <div class="tile-amount">0</div>
              <?php common::printLink('story', 'create', "productID={$product->id}", '<span class="label label-badge label-icon"><i class="icon icon-plus"></i></span>' . $lang->story->create, '', 'class="btn btn-primary btn-circle btn-icon-left btn-sm"');?>
              <?php endif;?>
            </div>
            <ul class="types-line">
              <?php foreach($config->statistic->storyStages as $stage):?>
              <li>
                <div>
                  <small><?php echo zget($lang->story->stageList, $stage);?></small>
                  <span><?php echo $product->stories ? zget($product->stories, $stage, 0) : 0;?></span>
                </div>
              </li>
              <?php endforeach;?>
            </ul>
          </div>
          <?php if($product->stories):?>
          <div class="col-5">
            <div class="product-info">
              <?php $totalPlan     = $product->plans ? array_sum($product->plans) : 0;?>
              <?php $unexpiredPlan = $product->plans ? zget($product->plans, 'unexpired', 0) : 0;?>
              <?php $unexpiredRate = $totalPlan ? round($unexpiredPlan / $totalPlan * 100, 2) : 0;?>
              <?php if($totalPlan):?>
              <div class="progress-info"></div>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $unexpiredRate;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $unexpiredRate;?>%"></div>
              </div>
              <?php else:?>
              <div class="actions">
                <?php common::printLink('productplan', 'create', "productID={$product->id}", "<i class='icon icon-plus'></i>" . $lang->productplan->create, '', "class='btn btn-info'");?>
              </div>
              <?php endif;?>
              <div class="type-info">
                <div class="type-label">
                  <table class='status-count'>
                    <tr>
                      <td class='text-right'><?php echo $lang->productplan->all;?> :</td>
                      <td class='text-left'><?php echo empty($totalPlan) ? 0 : html::a($this->createLink('productplan', 'browse', "productID={$product->id}&branch=0&browseType=all"), $totalPlan);?></td>
                    </tr>
                    <tr>
                      <td class='text-right'><?php echo $lang->productplan->featureBar['browse']['unexpired'];?> :</td>
                      <td class='text-left'><?php echo empty($unexpiredPlan) ? 0 : html::a($this->createLink('productplan', 'browse', "productID={$product->id}&branch=0&browseType=unexpired"), $unexpiredPlan);?></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="product-info">
              <?php $totalProject = $product->projects ? zget($product->projects, 'all', 0) : 0;?>
              <?php $undoneProject = $product->projects ? zget($product->projects, 'undone', 0) : 0;?>
              <?php $delayProject = $product->projects ? zget($product->projects, 'delay', 0) : 0;?>
              <?php $undoneRate    = $totalProject ? round($undoneProject / $totalProject * 100, 2) : 0;?>
              <?php if($totalProject):?>
              <div class="progress-info">
                <?php if($delayProject):?>
                <i class="icon icon-exclamation-sign text-danger icon-sm"></i> <span class="text-muted"><?php echo $lang->project->delayed;?></span> <strong><?php echo $delayProject;?></strong>
                <?php endif;?>
              </div>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $undoneRate;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $undoneRate;?>%"></div>
              </div>
              <?php else:?>
              <div class="actions">
                <?php common::printLink('project', 'create', '', "<i class='icon icon-plus'></i>" . $lang->project->create, '', "class='btn btn-info'");?>
              </div>
              <?php endif;?>
              <div class="type-info">
                <div class="type-label">
                  <table class='status-count'>
                    <tr>
                      <td class='text-right'><?php echo $lang->project->allProjects;?> :</td>
                      <td class='text-left'><?php echo empty($totalProject) ? 0 : html::a($this->createLink('product', 'project', "type=all&product={$product->id}"), $totalProject);?></td>
                    </tr>
                    <tr>
                      <td class='text-right'><?php echo $lang->project->statusList['doing'];?> :</td>
                      <td class='text-left'><?php echo empty($undoneProject) ? 0 : html::a($this->createLink('product', 'project', "type=undone&product={$product->id}"), $undoneProject);?></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="product-info">
              <?php $totalRelease  = $product->releases ? array_sum($product->releases) : 0;?>
              <?php $normalRelease = $product->releases ? zget($product->releases, 'normal', 0) : 0;?>
              <?php $normalRate    = $totalRelease ? round($normalRelease / $totalRelease * 100, 2) : 0;?>
              <?php if($totalRelease):?>
              <div class="progress-info">
                <?php if($product->lastRelease):?>
                <i class="icon icon-check-circle text-success icon-sm"></i> <span class="text-muted"><?php echo $lang->release->yesterday;?></span> <strong><?php echo $product->lastRelease;?></strong>
                <?php endif;?>
              </div>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $normalRate;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $normalRate;?>%"></div>
              </div>
              <?php else:?>
              <div class="actions">
                <?php common::printLink('release', 'create', "productID={$product->id}", "<i class='icon icon-plus'></i>" . $lang->release->create, '', "class='btn btn-info'");?>
              </div>
              <?php endif;?>
              <div class="type-info">
                <div class="type-label">
                  <table class='status-count'>
                    <tr>
                      <td class='text-right'><?php echo $lang->product->allRelease;?> :</td>
                      <td class='text-left'><?php echo empty($totalRelease) ? 0 : html::a($this->createLink('release', 'browse', "productID={$product->id}&branch=0&type=all"), $totalRelease);?></td>
                    </tr>
                    <tr>
                      <td class='text-right'><?php echo $lang->product->maintain;?> :</td>
                      <td class='text-left'><?php echo empty($normalRelease) ? 0 : html::a($this->createLink('release', 'browse', "productID={$product->id}&branch=0&type=normal"), $normalRelease);?></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <?php endif;?>
        </div>
      </div>
      <?php endforeach;?>
    </div>
    <?php endif;?>
  </div>
</div>
