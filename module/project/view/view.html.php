<?php
/**
 * The view method view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: view.html.php 4594 2013-03-13 06:16:02Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
  <div class="main-row">
    <div class="col-8 main-col">
      <div class="row">
        <div class="col-sm-6">
          <div class="panel block-release">
            <div class="panel-heading">
              <div class="panel-title"><?php echo $lang->project->iteration;?> <span class="label label-badge label-light"><?php echo sprintf($lang->project->iterationInfo, count($builds));?></span></div>
            </div>
            <div class="panel-body">
              <div class="release-path">
                <ul class="release-line">
                  <?php $i = 0;?>
                  <?php foreach(array_reverse($builds) as $build):?>
                  <?php $i++;?>
                  <?php if($i > 6) break;?>
                  <li <?php if(date('Y-m-d') < $build->date) echo "class='active'";?>>
                    <a href="<?php echo $this->createLink('build', 'view', "buildID={$build->id}");?>">
                      <?php if(!empty($build->marker)) echo "<i class='icon icon-flag text-primary'></i>";?>
                      <span class="title"><?php echo $build->name;?></span>
                      <span class="date"><?php echo $build->date;?></span>
                      <span class="info"><?php echo $build->desc;?></span>
                    </a>
                  </li>
                  <?php endforeach;?>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="panel block-dynamic">
            <div class="panel-heading">
              <div class="panel-title"><?php echo $lang->project->latestDynamic;?></div>
              <nav class="panel-actions nav nav-default">
                <li><a href="<?php echo $this->createLink('project', 'dynamic', "projectID=$project->id&type=all")?>" title="<?php echo $lang->more;?>">MORE</i></a></li>
              </nav>
            </div>
            <div class="panel-body">
              <ul class="timeline timeline-tag-left">
                <?php foreach($actions as $action):?>
                <li <?php if($action->actor == $this->app->user->account) echo "class='active'";?>>
                  <div>
                    <span class="timeline-tag"><?php echo $action->date;?></span>
                    <span class="timeline-text"><?php echo zget($users, $action->actor) . ' ' . $action->actionLabel . $action->objectLabel . ' ' . html::a($action->objectLink, $action->objectName);?></span>
                  </div>
                </li>
                <?php endforeach;?>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-sm-12">
          <?php $blockHistory = true;?>
          <?php include '../../common/view/action.html.php';?>
        </div>
      </div>
    </div>
    <div class="col-4 side-col">
      <div class="row">
        <div class="col-sm-12">
          <div class="cell">
            <div class="detail">
              <h2 class="detail-title"><span class="label-id"><?php echo $project->id;?></span> <span class="label label-light label-outline"><?php echo $project->code;?></span> <?php echo $project->name;?></h2>
              <div class="detail-content article-content">
                <p><span class="text-limit" data-limit-size="40"><?php echo $project->desc;?></span><a class="text-primary text-limit-toggle small" data-text-expand="<?php echo $lang->expand;?>"  data-text-collapse="<?php echo $lang->collapse;?>"></a></p>
                <p>
                  <?php if($project->deleted):?>
                  <span class='label label-danger label-outline'><?php echo $lang->project->deleted;?></span>
                  <?php endif; ?>
                  <span class="label label-primary label-outline"><?php echo zget($lang->project->typeList, $project->type);?></span>
                  <?php if(isset($project->delay)):?>
                  <span class="label label-danger label-outline"><?php echo $lang->project->delayed;?></span>
                  <?php else:?>
                  <span class="label label-success label-outline"><?php echo zget($lang->project->statusList, $project->status);?></span>
                  <?php endif;?>
                </p>
              </div>
            </div>
            <div class='detail'>
              <div class='detail-title'><strong><?php echo $lang->project->lblStats;?></strong></div>
              <div class="detail-content">
                <table class='table table-data data-stats'>
                  <tbody>
                    <tr>
                      <th><?php echo $lang->project->totalHours;?></th>
                      <td><em><?php echo $project->totalHours;?></em></td>
                      <th><?php echo $lang->project->totalEstimate;?></th>
                      <td><em><?php echo $project->totalEstimate;?></em></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->project->totalConsumed;?></th>
                      <td><em><?php echo $project->totalConsumed;?></em></td>
                      <th><?php echo $lang->project->totalLeft;?></th>
                      <td><em><?php echo $project->totalLeft;?></em></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <?php if($this->config->global->flow != 'onlyTask'):?>
            <div class="detail">
              <div class="detail-title"><strong><?php echo $lang->project->owner;?></strong></div>
              <div class="detail-content">
                <table class="table table-data">
                  <tbody>
                    <tr>
                      <th><i class="icon icon-person icon-sm"></i> <?php echo $lang->projectCommon;?></th>
                      <td><em><?php echo zget($users, $project->PM);?></em></td>
                      <th><i class="icon icon-person icon-sm"></i> <?php echo $lang->productCommon;?></th>
                      <td><em><?php echo zget($users, $project->PO);?></em></td>
                    </tr>
                    <tr>
                      <th><i class="icon icon-person icon-sm"></i> <?php echo $lang->project->qa;?></th>
                      <td><em><?php echo zget($users, $project->QD);?></em></td>
                      <th><i class="icon icon-person icon-sm"></i> <?php echo $lang->project->release;?></th>
                      <td><em><?php echo zget($users, $project->RD);?></em></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <?php endif;?>
            <div class="detail">
              <div class="detail-title"><strong><?php echo $lang->project->basicInfo;?></strong></div>
              <div class="detail-content">
                <table class="table table-data data-basic">
                  <tbody>
                    <tr>
                      <th><?php echo $lang->project->beginAndEnd;?></th>
                      <td><em><?php echo $project->begin . ' ~ ' . $project->end;?></em></td>
                    <tr>
                      <th><?php echo $lang->project->days;?></th>
                      <td><em><?php echo $project->days;?></em></td>
                    </tr>
                    <?php if($this->config->global->flow != 'onlyTask'):?>
                    <tr>
                      <th><?php echo $lang->project->products;?></th>
                      <td>
                        <em>
                          <?php 
                          foreach($products as $productID => $product) 
                          {
                              if($product->type !== 'normal')
                              {
                                  $branchName = isset($branchGroups[$productID][$product->branch]) ? '/' . $branchGroups[$productID][$product->branch] : '';
                                  echo html::a($this->createLink('product', 'browse', "productID=$productID&branch=$product->branch"), $product->name . $branchName);
                              }
                              else
                              {
                                  echo html::a($this->createLink('product', 'browse', "productID=$productID"), $product->name);
                              }
                              echo '<br />';
                          }
                          ?>
                        </em> 
                      </td>
                    </tr>
                    <?php endif;?>
                    <tr>
                      <th><?php echo $lang->project->acl;?></th>
                      <td><em><?php echo $lang->project->aclList[$project->acl];?></em></td>
                    </tr>  
                    <?php if($project->acl == 'custom'):?>
                    <tr>
                      <th><?php echo $lang->project->whitelist;?></th>
                      <td>
                        <em>
                        <?php
                        $whitelist = explode(',', $project->whitelist);
                        foreach($whitelist as $groupID) if(isset($groups[$groupID])) echo $groups[$groupID] . '&nbsp;';
                        ?>
                        </em>
                      </td>
                    </tr>  
                    <?php endif;?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="mainActions">
  <nav class="container"></nav>
  <div class="btn-toolbar">
    <?php
    $params = "project=$project->id";
    $browseLink = $this->session->projectList ? $this->session->projectList : inlink('browse', "projectID=$project->id");
    common::printBack($browseLink);
    if(!$project->deleted)
    {
        echo "<div class='divider'></div>";
        common::printIcon('project', 'start',    "projectID=$project->id", $project, 'button', '', '', 'iframe', true);
        common::printIcon('project', 'activate', "projectID=$project->id", $project, 'button', '', '', 'iframe', true);
        common::printIcon('project', 'putoff',   "projectID=$project->id", $project, 'button', '', '', 'iframe', true);
        common::printIcon('project', 'suspend',  "projectID=$project->id", $project, 'button', '', '', 'iframe', true);
        common::printIcon('project', 'close',    "projectID=$project->id", $project, 'button', '', '', 'iframe', true);

        echo "<div class='divider'></div>";
        common::printIcon('project', 'edit', $params, $project);
        common::printIcon('project', 'delete', $params, $project, 'button', '', 'hiddenwin');
    }
    else
    {
        common::printBack($browseLink);
    }
    ?>
  </div>
<?php include '../../common/view/footer.html.php';?>
