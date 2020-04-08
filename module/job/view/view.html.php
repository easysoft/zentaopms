<?php
/**
 * The view file of job module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     job
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php common::printBack($this->createLink('job', 'browse'), 'btn btn-primary');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='label label-id'><?php echo $job->id;?></span>
      <span title='<?php echo $job->name;?>' class='text'><?php echo $job->name;?></span>
    </div>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <?php $hasResult = ($compile and !empty($compile->testtask));?>
  <?php $hasLog    = ($compile and !empty($compile->logs));?>
  <div class='tabs' id='tabsNav'>
    <ul class='nav nav-tabs'>
      <?php if($hasResult):?>
      <li class='active'><a href='#testresult' data-toggle='tab'><?php echo $lang->compile->result;?></a></li>
      <?php endif;?>
      <?php if($hasLog):?>
      <li class='<?php echo $hasResult ? '' : 'active';?>'><a href='#logs' data-toggle='tab'><?php echo $lang->compile->logs;?></a></li>
      <?php endif;?>
      <li class='<?php echo ($hasResult || $hasLog) ? '' : 'active';?>'><a href='#info' data-toggle='tab'><?php echo $lang->job->lblBasic;?></a></li>
    </ul>
    <div class='tab-content'>
      <?php if($hasResult):?>
      <div id='testresult' class='tab-pane active'>
        <?php include $this->app->getModuleRoot() . 'testtask/view/unitgroup.html.php';?>
      </div>
      <?php endif;?>
      <?php if($hasLog):?>
      <div id='logs' class='tab-pane <?php echo $hasResult ? '' : 'active';?>'>
        <div class='main-content'><?php echo nl2br($compile->logs);?></div>
      </div>
      <?php endif;?>
      <div id='info' class='tab-pane <?php echo ($hasResult || $hasLog) ? '' : 'active';?>'>
        <table class='table table-data table-condensed table-borderless'>
          <tr>
            <th class='w-100px strong'><?php echo $lang->job->repo;?></th>
            <td><?php echo $repo->name;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->job->product;?></th>
            <td><?php if($product) echo $product->name;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->job->frame;?></th>
            <td><?php echo zget($lang->job->frameList, $job->frame);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->job->jkHost;?></th>
            <td><?php echo urldecode($job->jkJob) . '@' . $jenkins->name;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->job->triggerType;?></th>
            <td><?php echo $this->job->getTriggerConfig($job);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->compile->status;?></th>
            <td>
              <?php
              if($compile and $compile->status)
              {
                  echo zget($lang->compile->statusList, $compile->status);
              }
              elseif($job->lastStatus)
              {
                  echo zget($lang->compile->statusList, $job->lastStatus);
              }
              ?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->compile->time;?></th>
            <td>
              <?php
              if($compile and $compile->status)
              {
                  echo zget($lang->compile->statusList, $compile->updateDate);
              }
              elseif($job->lastStatus)
              {
                  echo zget($lang->compile->statusList, $job->lastExec);
              }
              ?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>

