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
<div class="main-row spilter-row">
  <div class="side-col" style="width: 220px" data-min-width="220">
    <div class="cell">
      <header class="table-row space">
        <form method='post' action='<?php echo '';?>'>
          <div class="input-control has-icon-right table-col">
            <input id="searchLib" type="search" class="form-control" placeholder="<?php echo $this->lang->doc->searchDoc;?>">
            <label for="searchLib" class="input-control-icon-right"><i class="icon icon-search"></i></label>
          </div>
        </form>
        <div class="table-col text-right c-sm">
          <?php echo html::a($this->createLink('doc', 'createLib'), "<i class='icon icon-plus'></i>", '', "class='btn btn-secondary btn-icon iframe'");?>
        </div>
      </header>
      <ul id="docsTree" data-ride="tree" class="tree no-margin">
        <li class="open">
          <a class="text-muted tree-toggle"><?php echo $lang->doc->fast;?></a>
          <ul>
            <?php foreach($lang->doc->fastMenuList as $type => $menu):?>
            <?php
            if($type == 'editedDate' or $type == 'visitedDate')
            {
                $link = $this->createLink('doc', 'browse', "libID=0&browseTyp=bymenu&module=0&orderBy={$type}_desc");
            }
            else
            {
                $link = $this->createLink('doc', 'browse', "libID=0&browseTyp={$type}");
            }
            ?>
            <li><?php echo html::a($link, "<i class='icon {$lang->doc->fastMenuIconList[$type]}'></i> {$menu}");?>
            <?php endforeach;?>
          </ul>
        </li>
        <li class="open">
          <a class="text-muted tree-toggle"><?php echo $lang->productCommon;?></a>
          <ul>
            <?php foreach($products as $product):?>
            <li>
              <?php echo html::a($this->createLink('doc', 'objectLibs', "type=product&objectID=$product->id"), "<i class='icon icon-cube'></i> " . $product->name);?>
              <?php if(isset($subLibs['product'][$product->id])):?>
              <ul>
                <?php foreach($subLibs['product'][$product->id] as $libID => $libName):?>
                <?php
                if($libID == 'project')
                {
                    $libLink = inlink('allLibs', "type=project&product=$product->id");
                    $icon    = 'icon-stack';
                }
                elseif($libID == 'files')
                {
                    $libLink = inlink('showFiles', "type=product&objectID=$product->id");
                    $icon    = 'icon-paper-clip';
                }
                else  
                {
                    $libLink = inlink('browse', "libID=$libID");
                    $icon    = 'icon-folder-outline';
                }
                ?>
                <li>
                  <?php echo html::a($libLink, "<i class='icon {$icon}'></i> " . $libName);?>
                  <?php if(isset($modules[$libID])):?>
                  <ul>
                    <?php foreach($modules[$libID] as $module):?>
                    <li><?php echo html::a($this->createLink('doc', 'browse', "libID=$libID&browseType=byModule&param={$module->id}"), "<i class='icon icon-folder-outline'></i> " . $module->name);?></li>
                    <?php endforeach;?>
                  </ul>
                  <?php endif;?>
                </li>
                <?php endforeach;?>
              </ul>
              <?php endif;?>
            </li>
            <?php endforeach;?>
          </ul>
        </li>
        <li>
          <a class="text-muted tree-toggle"><?php echo $lang->projectCommon;?></a>
          <ul>
            <?php foreach($projects as $project):?>
            <li class="open">
              <?php echo html::a($this->createLink('doc', 'objectLibs', "type=project&objectID=$project->id"), "<i class='icon icon-cube'></i> " . $project->name);?>
              <?php if(isset($subLibs['project'][$project->id])):?>
              <ul>
                <?php foreach($subLibs['project'][$project->id] as $libID => $libName):?>
                <?php
                if($libID == 'files')
                {
                    $libLink = inlink('showFiles', "type=project&objectID=$project->id");
                    $icon = 'icon-paper-clip';
                }
                else 
                {
                    $libLink = inlink('browse', "libID=$libID");
                    $icon = 'icon-paper-outline';
                }
                ?>
                <li>
                  <?php echo html::a($libLink, "<i class='icon $icon'></i> " . $libName);?>
                  <?php if(isset($modules[$libID])):?>
                  <ul>
                    <?php foreach($modules[$libID] as $module):?>
                    <li><?php echo html::a($this->createLink('doc', 'browse', "libID=$libID&browseType=byModule&param={$module->id}"), "<i class='icon icon-folder-outline'></i> " . $module->name);?></li>
                    <?php endforeach;?>
                  </ul>
                  <?php endif;?>
                </li>
                <?php endforeach;?> 
              </ul>
              <?php endif;?>
            </li>
            <?php endforeach;?>
          </ul>
        </li>
        <li>
          <a class="text-muted tree-toggle"><?php echo $lang->doc->custom;?></a>
          <ul>
            <?php foreach($customLibs as $libID => $libName):?>
            <li>
              <?php echo html::a(inlink('browse', "libID=$libID"), "<i class='icon icon-folder-outline'></i> " . $libName);?>
              <?php if(isset($modules[$libID])):?>
              <ul>
                <?php foreach($modules[$libID] as $module):?>
                <li><?php echo html::a($this->createLink('doc', 'browse', "libID=$libID&browseType=byModule&param={$module->id}"), "<i class='icon icon-folder-outline'></i> " . $module->name);?></li>
                <?php endforeach;?>
              </ul>
              <?php endif;?>
            </li>
            <?php endforeach;?>
          </ul>
        </li>
      </ul>
    </div>
  </div>
  <div class="main-col" data-min-width="400">
    <div class="row">
      <div class="col-sm-7">
        <div class="panel block-files block-sm" style="height: 290px;">
          <div class="panel-heading">
          <div class="panel-title"><?php echo $lang->doc->orderByEdit;?></div>
            <nav class="panel-actions nav nav-default">
              <li><?php echo html::a($this->createLink('doc', 'browse', "libID=0&browseTyp=bymenu&module=0&orderBy=editeddate_desc"), 'MORE', '', "title='{$lang->more}'");?></li>
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
                  <td class="c-num text-right"><?php echo $doc->fileSize;?></td>
                  <td class="c-user"><?php echo zget($users, $doc->addedBy);?></td>
                  <td class="c-datetime"><?php echo formatTime($doc->editedDate, 'm-d H:i');?></td>
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
              <div class="table-row text-center small text-muted with-padding">
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
                  <small class="muted"><?php echo $lang->doc->orderByVisit;?></small>
                  <div class="strong"><?php echo $statisticInfo->lastVisitedDocs;?></div>
                </div>
                <div class="table-col text-middle">
                  <div class="progress-pie inline-block" data-value="<?php echo $statisticInfo->lastVisitedProgress;?>" data-doughnut-size="78" data-color="#00a9fc">
                    <canvas width="50" height="50"></canvas>
                    <div class="progress-info">
                      <strong><span class="progress-value"><?php echo $statisticInfo->lastVisitedProgress;?></span><small>%</small></strong>
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
            <div class="panel-title"><?php echo $lang->project->statusList['doing'] . $lang->projectCommon;?></div>
            <nav class="panel-actions nav nav-default">
              <li><?php echo html::a($this->createLink('doc', 'allLibs', 'type=project'), 'MORE', '', "title='{$lang->more}'");?></li>
            </nav>
          </div>
          <div class="panel-body has-table">
            <table class="table table-borderless table-fixed-head table-hover">
              <thead>
                <tr>
                  <th class="c-name"><?php echo $lang->project->name;?></th>
                  <th class="c-user"><?php echo $lang->doc->addedBy;?></th>
                  <th class="c-datetime"><?php echo $lang->doc->addedDate;?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($doingProjects as $project):?>
                <tr data-url="<?php echo $this->createLink('doc', 'objectLibs', "type=project&objectID={$project->id}")?>">
                  <td class="c-name"><i class="icon icon-folder text-yellow"></i> <?php echo $project->name;?></td>
                  <td class="c-user"><?php echo zget($users, $project->openedBy);?></td>
                  <td class="c-datetime"><?php echo formatTime($project->openedDate, 'm-d H:i');?></td>
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
              <li><?php echo html::a($this->createLink('doc', 'browse', "libID=0&browseTyp=openedbyme"), 'MORE', '', "title='{$lang->more}'");?></li>
            </nav>
          </div>
          <div class="panel-body has-table">
            <table class="table table-borderless table-fixed-head table-hover">
              <thead>
                <tr>
                  <th class="c-name"><?php echo $lang->doc->title;?></th>
                  <th class="c-user"><?php echo $lang->doc->addedBy;?></th>
                  <th class="c-datetime"><?php echo $lang->doc->addedDate;?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($myDocs as $doc):?>
                <tr data-url="">
                  <td class="c-name"><?php echo $doc->title;?></td>
                  <td class="c-user"><?php echo zget($users, $doc->addedBy);?></td>
                  <td class="c-datetime"><?php echo formatTime($doc->addedDate, 'm-d H:i');?></td>
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
