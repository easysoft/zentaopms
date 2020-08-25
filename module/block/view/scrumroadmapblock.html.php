<?php
/**
 * The roadmap view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: roadmap.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<style>
.release-line>li:nth-child(even)>a{height:92px;}
.release-line>li:nth-child(odd){padding-top: 87px;}
#dashboard .panel-move-handler{right:78px}
#productList{width:28%; position: absolute; top: 6px; left: 96px;}
#createPlan{position: absolute; top: 3px; right:0px;}
#createPlan a{padding: 0 5px;line-height: 24px;margin: 3px;color: #3C4353;}
</style>
<div class="panel-move-handler">
<div id="productList"><?php echo html::select('productID', $products, '', 'class="form-control chosen"');?></div>
<div id="createPlan"><?php echo html::a($this->createLink('productplan', 'create', 'productID=' . $productID), '<i class="icon icon-sm icon-plus"></i>'. $lang->productplan->create, '', 'class="btn btn-mini" id="createPlan"');?></div>
</div>
<div class="panel-body conatiner-fluid">
  <div class="release-path">
    <ul class="release-line">
      <?php foreach($roadmaps as $year => $mapBranches):?>
        <?php foreach($mapBranches as $plans):?>
          <?php foreach($plans as $plan):?>
            <?php if(isset($plan->begin)):?>
            <li <?php if(date('Y-m-d') < $plan->begin) echo 'class="active"';?>>
              <a href="<?php echo $this->createLink('productplan', 'view', "planID={$plan->id}");?>">
                <span class="title" title="<?php echo $plan->title;?>"><?php echo $plan->title;?></span>
                <span class="date" title="<?php echo $plan->begin;?>"><?php echo $plan->begin;?></span>
                <span class="date"><?php echo $lang->block->estimatedHours;?> <?php echo $plan->hour;?> h</span>
              </a>
            </li>
            <?php else:?>
            <li>
              <a href="<?php echo $this->createLink('release', 'view', "releaseID={$plan->id}");?>">
                <span class="title" title="<?php echo $plan->name;?>"><?php echo $plan->name . "($plan->buildName)";?></span>
                <span class="date" title="<?php echo $plan->date;?>"><?php echo $plan->date;?></span>
                <?php $estimate = empty($plan->stories) ? 0 : $this->block->getStorysEstimateHours(explode(',', $plan->stories));?>
                <span class="date"><?php echo $lang->block->consumedHours;?> <?php echo empty($estimate) ? 0 : $estimate;?> h</span>
              </a>
            </li>
            <?php endif;?>
          <?php endforeach;?>
        <?php endforeach;?>
      <?php endforeach;?>
    </ul>
  </div>
</div>
