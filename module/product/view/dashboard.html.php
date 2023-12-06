<?php
/**
 * The view view of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: view.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class="main-row">
  <div class="col-12 main-col">
    <div class="row">
    <?php $isRoadmap = (common::hasPriv('product', 'roadmap') and helper::hasFeature('product_roadmap'));?>
    <?php if($isRoadmap):?>
      <div class="col-sm-6">
        <div class="panel block-release">
          <div class="panel-heading">
            <div class="panel-title"><?php echo $lang->product->roadmap;?></div>
          </div>
          <div class="panel-body">
            <div class="release-path">
              <ul class="release-line">
                <?php foreach($roadmaps as $roadmap):?>
                <?php if(isset($roadmap->begin)):?>
                <li <?php if(date('Y-m-d') < $roadmap->begin) echo "class='active'";?>>
                  <a href="<?php echo $this->createLink('productplan', 'view', "planID={$roadmap->id}");?>">
                    <span class="title" title='<?php echo $roadmap->title;?>'><?php echo $roadmap->title;?></span>
                    <span class="date"><?php echo $roadmap->begin;?></span>
                  </a>
                </li>
                <?php else:?>
                <li>
                  <a href="<?php echo $this->createLink('release', 'view', "releaseID={$roadmap->id}");?>">
                    <span class="title" title='<?php echo $roadmap->name;?>'><?php echo $roadmap->name;?></span>
                    <span class="date"><?php echo $roadmap->date;?></span>
                  </a>
                </li>
                <?php endif;?>
                <?php endforeach;?>
              </ul>
            </div>
            <?php echo html::a($this->createLink('product', 'roadmap', "productID={$product->id}"), $lang->product->iterationView . "<span class='label label-badge label-icon'><i class='icon icon-arrow-right'></i></span>", '', "class='btn btn-primary btn-circle btn-icon-right btn-sm pull-right'");?>
          </div>
        </div>
      </div>
      <?php endif;?>
      <div class="col-sm-<?php echo $isRoadmap ? 6 : 12?>">
        <div class="panel block-dynamic">
          <div class="panel-heading">
          <div class="panel-title"><?php echo $lang->product->latestDynamic;?></div>
            <nav class="panel-actions nav nav-default">
              <li><a href="<?php echo $this->createLink('product', 'dynamic', "productID={$product->id}&type=all");?>" title="<?php echo $lang->more;?>"><?php echo strtoupper($lang->more);?></i></i></a></li>
            </nav>
          </div>
          <div class="panel-body scrollbar-hover">
            <ul class="timeline timeline-tag-left no-margin">
              <?php foreach($dynamics as $action):?>
              <li <?php if($action->major) echo "class='active'";?>>
                <div>
                  <span class="timeline-tag"><?php echo $action->date;?></span>
                  <span class="timeline-text"><?php echo zget($users, $action->actor) . ' ' . "<span class='label-action'>{$action->actionLabel}</span>" . $action->objectLabel . ' ' . html::a($action->objectLink, $action->objectName);?></span>
                </div>
              </li>
              <?php endforeach;?>
            </ul>
          </div>
        </div>
      </div>
      <?php $this->printExtendFields($product, 'div', "position=left&inForm=0");?>
    </div>
  </div>
</div>
<div id="mainActions" class='main-actions'>
  <nav class="container"></nav>
</div>
<?php include '../../common/view/footer.html.php';?>
