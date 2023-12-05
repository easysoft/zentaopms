<?php
/**
 * The roadmap view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: roadmap.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='main-content' id='mainContent'>
  <h2><?php echo $lang->product->iteration;?> <span class="label label-badge label-light"><?php echo sprintf($lang->product->iterationInfo, $roadmaps['total']);?></span></h2>
  <?php if($product->type != 'normal'):?>
  <div class="text-center branch-nav">
    <ul class="nav nav-secondary inline-block">
      <li class="nav-heading"><?php echo zget($lang->product->branchName, $product->type);?></li>
      <?php foreach($branches as $branchKey => $branchName):?>
      <li title="<?php echo $branchName;?>" <?php if($branchKey == 0) echo "class='active'";?>><a data-target="#tabContent<?php echo $branchKey;?>" data-toggle="tab"><?php echo $branchName;?></a></li>
      <?php endforeach;?>
    </ul>
  </div>
  <?php endif;?>
  <div class="tab-content">
    <?php foreach($branches as $branchKey => $branchName):?>
    <div class="tab-pane fade release-paths <?php if($branchKey == 0) echo 'active in';?>" id="tabContent<?php echo $branchKey;?>">
      <?php
      $hasRoadmaps = false;
      foreach($roadmaps as $year => $yearRoadmaps)
      {
          if(isset($yearRoadmaps[$branchKey]))
          {
              $hasRoadmaps = true;
              break;
          }
      }
      ?>
      <?php if(!$hasRoadmaps):?>
      <div class="table-empty-tip">
        <p>
          <span class="text-muted"><?php echo $lang->release->noRelease;?></span>
          <?php if(common::canModify('product', $product) and common::hasPriv('release', 'create')):?>
          <?php echo html::a($this->createLink('release', 'create', "productID=$product->id&branch=$branchKey"), "<i class='icon icon-plus'></i> " . $lang->release->create, '', "class='btn btn-info'");?>
          <?php endif;?>
        </p>
      </div>
      <?php else:?>
      <?php foreach($roadmaps as $year => $yearRoadmaps):?>
      <?php if(!isset($yearRoadmaps[$branchKey])) continue;?>
      <?php $groupRoadmaps = zget($yearRoadmaps, $branchKey, array());?>
      <div class="release-path">
        <div class="release-head">
          <div class="title text-primary"><?php echo $year . (is_numeric($year) ? (common::checkNotCN() ? '' : $lang->year) : '');?></div>
          <div class="subtitle"><?php echo sprintf($lang->product->iterationInfo, count($groupRoadmaps, 1) - count($groupRoadmaps));?></div>
        </div>
        <?php $i = 0;?>
        <?php foreach($groupRoadmaps as $row => $roadmapData):?>
        <ul class="release-line">
          <?php foreach($roadmapData as $roadmap):?>
          <li <?php if(isset($roadmap->build) && date('Y-m-d') < $roadmap->date) echo "class='active'";?>>
            <?php $viewLink = isset($roadmap->build) ? $this->createLink('release', 'view', "releaseID=$roadmap->id") : $this->createLink('productplan', 'view', "planID=$roadmap->id");?>
            <a href="<?php echo $viewLink;?>">
              <?php if(!empty($roadmap->marker)):?>
              <i class="icon icon-flag text-primary"></i>
              <?php endif;?>
              <div class="block">
                <?php $roadmapTitle = isset($roadmap->build) ? $roadmap->name : $roadmap->title;?>
                <span class="title" title='<?php echo $roadmapTitle;?>'><?php echo $roadmapTitle;?></span>
                <span class="date"><?php echo isset($roadmap->build) ? $roadmap->date : ($roadmap->end == '2030-01-01' ? $lang->productplan->future : $roadmap->begin . '~' . $roadmap->end);?></span>
              </div>
            </a>
          </li>
          <?php endforeach;?>
        </ul>
        <?php endforeach;?>
      </div>
      <?php endforeach;?>
      <?php endif;?>
    </div>
    <?php endforeach;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
