<?php
/**
 * The index view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='main-row split-row fade' id='mainRow'>
  <?php include './side.html.php';?>
  <div class="main-col" data-min-width="400">
  <div class="cell" id="queryBox" data-module='doc'></div>
    <div class="row">
      <div class="col-sm-7">
        <div class="panel block-files block-sm" style="height: 290px;">
          <div class="panel-heading">
          <div class="panel-title"><?php echo $lang->doc->orderByEdit;?></div>
            <nav class="panel-actions nav nav-default">
              <li><?php echo html::a($this->createLink('doc', 'browse', "libID=0&browseTyp=byediteddate"), '<i class="icon icon-more icon-sm"></i>', '', "title='{$lang->more}'");?></li>
            </nav>
          </div>
          <div class="panel-body has-table">
            <table class="table table-borderless table-fixed-head table-hover">
              <thead>
                <tr>
                  <th class="c-name"><?php echo $lang->doc->title;?></th>
                  <th class="c-num text-right"><?php echo $lang->doc->size;?></th>
                  <th class="c-user"><?php echo $lang->doc->addedBy;?></th>
                  <th class="c-datetime"><?php echo $lang->doc->editedDate;?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($latestEditedDocs as $doc):?>
                <tr data-url="<?php echo $this->createLink('doc', 'view', "docID={$doc->id}");?>">
                  <td class="c-name"><?php echo $doc->title;?></td>
                  <td class="c-num text-right"><?php echo $doc->fileSize ? $doc->fileSize : '-';?></td>
                  <td class="c-user"><?php echo zget($users, $doc->addedBy);?></td>
                  <td class="c-datetime"><?php echo $doc->editedDate == '0000-00-00 00:00:00' ? formatTime($doc->addedDate, 'Y-m-d') : formatTime($doc->editedDate, 'Y-m-d');?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-sm-5">
        <div class="panel block-sm" style="height: 290px;">
          <div class="panel-heading">
            <div class="panel-title"><?php echo $lang->doc->allDoc . ' ' . $statisticInfo->totalDocs;?></div>
          </div>
          <div class="panel-body table-row">
            <div class="col-7 text-middle text-center">
              <div class="progress-pie inline-block space-lg" data-value="<?php echo $statisticInfo->lastEditedProgress;?>" data-doughnut-size="84" data-real-value="<?php echo $statisticInfo->lastEditedDocs;?>">
                <canvas width="100" height="100"></canvas>
                <div class="progress-info">
                  <small><?php echo $lang->doc->orderByEdit;?></small>
                  <strong class="progress-value"><?php echo $statisticInfo->lastEditedDocs;?></strong>
                </div>
              </div>
              <div class="table-row text-center small text-muted">
                <div class="col-4">
                  <span class="label label-dot label-primary"></span>
                  <span><?php echo $lang->doc->todayEdited;?></span>
                  <em class="strong"><?php echo $statisticInfo->todayEditedDocs;?></em>
              </div>
                <div class="col-4">
                  <span class="label label-dot label-pale"></span>
                  <span><?php echo $lang->doc->pastEdited;?></span>
                  <em class="strong"><?php echo $statisticInfo->pastEditedDocs;?></em>
                </div>
              </div>
            </div>
            <div class="col-5 text-middle text-center">
              <a class="table-row space-lg">
                <div class="table-col text-middle">
                  <small class="muted"><?php echo $lang->doc->orderByOpen;?></small>
                  <div class="strong"><?php echo $statisticInfo->lastAddedDocs;?></div>
                </div>
                <div class="table-col text-middle">
                  <div class="progress-pie inline-block" data-value="<?php echo $statisticInfo->lastAddedProgress;?>" data-doughnut-size="78" data-color="#00a9fc">
                    <canvas width="50" height="50"></canvas>
                    <div class="progress-info">
                      <strong><span class="progress-value"><?php echo $statisticInfo->lastAddedProgress;?></span><small>%</small></strong>
                    </div>
                  </div>
                </div>
              </a>
              <a class="table-row space-lg">
                <div class="table-col text-middle">
                  <small class="muted"><?php echo $lang->doc->myDoc;?></small>
                  <div class="strong"><?php echo $statisticInfo->myDocs;?></div>
                </div>
                <div class="table-col text-middle">
                  <div class="progress-pie inline-block" data-value="<?php echo $statisticInfo->myDocsProgress;?>" data-doughnut-size="78" data-color="#00da88">
                    <canvas width="50" height="50"></canvas>
                    <div class="progress-info">
                      <strong><span class="progress-value"><?php echo $statisticInfo->myDocsProgress;?></span><small><?php echo $lang->percent;?></small></strong>
                    </div>
                  </div>
                </div>
              </a>
              <a class="table-row">
                <div class="table-col text-middle">
                  <small class="muted"><?php echo $lang->doc->myCollection;?></small>
                  <div class="strong"><?php echo $statisticInfo->myCollection;?></div>
                </div>
                <div class="table-col text-middle">
                  <div class="progress-pie inline-block" data-value="<?php echo $statisticInfo->myCollectionProgress;?>" data-doughnut-size="78" data-color="#fdc137">
                    <canvas width="50" height="50"></canvas>
                    <div class="progress-info">
                      <strong><span class="progress-value"><?php echo $statisticInfo->myCollectionProgress;?></span><small><?php echo $lang->percent;?></small></strong>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-7">
        <div class="panel block-files block-sm" style="height: 290px;">
          <div class="panel-heading">
            <div class="panel-title"><?php echo $lang->project->undone . (common::checkNotCN() ? "{$lang->projectCommon}s" : "$lang->projectCommon");?></div>
            <nav class="panel-actions nav nav-default">
              <li><?php echo html::a($this->createLink('doc', 'allLibs', 'type=project'), '<i class="icon icon-more icon-sm"></i>', '', "title='{$lang->more}'");?></li>
            </nav>
          </div>
          <div class="panel-body has-table">
            <table class="table table-borderless table-fixed-head table-hover">
              <thead>
                <tr>
                  <th class="c-name"><?php echo $lang->project->name;?></th>
                  <th class="c-date"><?php echo $lang->project->begin;?></th>
                  <th class="c-date"><?php echo $lang->project->end;?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($doingProjects as $project):?>
                <tr data-url="<?php echo $this->createLink('doc', 'objectLibs', "type=project&objectID={$project->id}")?>">
                  <td class="c-name"><i class="icon icon-folder text-yellow"></i> <?php echo $project->name;?></td>
                  <td class="c-datetime"><?php echo formatTime($project->begin);?></td>
                  <td class="c-datetime"><?php echo formatTime($project->end);?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-sm-5">
        <div class="panel block-files block-sm" style="height: 290px;">
          <div class="panel-heading">
          <div class="panel-title"><?php echo $lang->doc->myDoc;?></div>
            <nav class="panel-actions nav nav-default">
              <li><?php echo html::a($this->createLink('doc', 'browse', "libID=0&browseTyp=openedbyme"), '<i class="icon icon-more icon-sm"></i>', '', "title='{$lang->more}'");?></li>
            </nav>
          </div>
          <div class="panel-body has-table">
            <table class="table table-borderless table-fixed-head table-hover">
              <thead>
                <tr>
                  <th class="c-name"><?php echo $lang->doc->title;?></th>
                  <th class="c-user"><?php echo $lang->doc->addedBy;?></th>
                  <th class="c-datetime"><?php echo $lang->doc->editedDate;?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($myDocs as $doc):?>
                <tr data-url="<?php echo $this->createLink('doc', 'view', "docID={$doc->id}");?>">
                  <td class="c-name"><?php echo $doc->title;?></td>
                  <td class="c-user"><?php echo zget($users, $doc->addedBy);?></td>
                  <td class="c-datetime"><?php echo formatTime($doc->editedDate) ? formatTime($doc->editedDate, 'Y-m-d') : formatTime($doc->addedDate, 'y-m-d');?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
