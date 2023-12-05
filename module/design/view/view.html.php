<?php
/**
 * The view of design module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     design
 * @version     $Id: view.html.php 4903 2020-09-02 09:32:59Z tianshujie@easycorp.ltd $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('type', $design->type);?>
<?php js::set('repos', $repos);?>
<?php js::set('projectID', $design->project);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php $browseLink = $app->session->designList != false ? $app->session->designList : $this->createLink('design', 'browse', "projectID=$design->project");?>
    <?php if(!isonlybody()) echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $design->id?></span>
      <span class="text" title="<?php echo $design->name;?>"><?php echo $design->name;?></span>
      <?php if($design->deleted):?>
      <span class='label label-danger'><?php echo $lang->design->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->design->desc;?></div>
        <div class="detail-content article-content">
          <?php echo $design->desc;?>
        </div>
      </div>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $design->files, 'fieldset' => 'true'));?>
    </div>
    <div class='cell'><?php include '../../common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php
        $backLink = $this->createLink('design', 'browse', "projectID=$design->project");
        common::printBack($app->session->designList != false ? $app->session->designList : $backLink);
        ?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$design->deleted):?>
        <?php
        common::printIcon('design', 'assignTo',   "designID=$design->id", $design, 'button', '', '', 'iframe showinonlybody', true);
        if(helper::hasFeature('devops')) common::printIcon('design', 'linkCommit', "designID=$design->id", $design, 'button', 'link', '', 'iframe showinonlybody', true, "id='linkCommit'");
        common::printIcon('design', 'edit',       "designID=$design->id", $design, 'button', 'alter');
        common::printIcon('design', 'delete',     "designID=$design->id", $design, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class='side-col col-4'>
    <div class='cell'>
      <div class="detail">
        <div class='detail-title'><?php echo $lang->design->basicInfo;?></div>
        <div class='detail-content'>
          <table class='table table-data'>
            <tr>
              <th><?php echo $lang->design->type;?></th>
              <td><?php echo zget($typeList, $design->type);?></td>
            </tr>
            <tr <?php if(empty($project->hasProduct)) echo "class='hide'";?>>
              <th><?php echo $lang->design->product;?></th>
              <td><?php echo $design->productName;?></td>
            </tr>
            <tr>
              <th><?php echo $lang->design->story;?></th>
              <?php $moduleName = empty($project->hasProduct) ? 'projectstory' : 'story';?>
              <td><?php echo $design->story ? html::a($this->createLink($moduleName, 'view', "id=$design->story"), zget($stories, $design->story)) : '';?></td>
            </tr>
            <tr>
              <th><?php echo $lang->design->submission;?></th>
              <td><?php echo $design->commit;?></td>
            </tr>
            <tr>
              <th><?php echo $lang->design->createdBy;?></th>
              <td><?php echo zget($users, $design->createdBy);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->design->createdDate;?></th>
              <td><?php echo substr($design->createdDate, 0, 11);?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
