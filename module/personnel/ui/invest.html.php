<?php
declare(strict_types=1);
/**
 * The invest view file of personnel module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     personnel
 * @link        https://www.zentao.net
 */
namespace zin;

dropmenu();
?>
<div class='main-table bg-white'>
  <?php if(!empty($investList)):?>
  <table class="table has-sort-head bordered table-fixed text-center">
    <thead>
      <tr>
        <th rowspan='2' class="c-role"><?php echo $lang->personnel->role;?></th>
        <th rowspan='2' class="c-user"> <?php echo $lang->personnel->name;?></th>
        <th rowspan='2'><?php echo $lang->personnel->projects;?></th>
        <th rowspan='2'><?php echo $lang->personnel->executions;?></th>
        <th colspan="2"><?php echo $lang->personnel->workingHours;?></th>
        <th colspan="3"><?php echo $lang->personnel->task;?></th>
        <th colspan="3"><?php echo $lang->personnel->bug;?></th>
        <th <?php echo $config->URAndSR ? "colspan='2'" : "rowspan='2'";?> class="c-story"><?php echo $lang->personnel->createStories;?></th>
        <?php if($this->config->edition == 'max' or $this->config->edition == 'ipd'): ?>
        <th colspan="3"><?php echo $lang->personnel->issue;?></th>
        <th colspan="3"><?php echo $lang->personnel->risk;?></th>
        <?php endif;?>
      </tr>
      <tr>
        <th><?php echo $lang->personnel->invest;?></th>
        <th><?php echo $lang->personnel->left;?></th>
        <th><?php echo $lang->personnel->created;?></th>
        <th><?php echo $lang->personnel->finished;?></th>
        <th><?php echo $lang->personnel->wait;?></th>
        <th><?php echo $lang->personnel->created;?></th>
        <th><?php echo $lang->personnel->resolved;?></th>
        <th><?php echo $lang->personnel->wait;?></th>
        <?php if($this->config->URAndSR):?>
        <th><?php echo $lang->personnel->UR;?></th>
        <th><?php echo $lang->personnel->SR;?></th>
        <?php endif;?>
        <?php if($this->config->edition == 'max' or $this->config->edition == 'ipd'): ?>
        <th><?php echo $lang->personnel->created;?></th>
        <th><?php echo $lang->personnel->resolved;?></th>
        <th><?php echo $lang->personnel->wait;?></th>
        <th><?php echo $lang->personnel->created;?></th>
        <th><?php echo $lang->personnel->resolved;?></th>
        <th><?php echo $lang->personnel->wait;?></th>
        <?php endif;?>
      </tr>
    </thead>
    <tbody class="sortable">
      <?php foreach($investList as $role => $personnelList):?>
      <?php foreach($personnelList as $personnel):?>
      <tr>
        <td title='<?php echo $personnel['role'];?>' style="overflow: hidden; white-space:nowrap; text-overflow: ellipsis;"><?php echo $personnel['role'];?></td>
        <td title='<?php echo $personnel['realname'];?>'><?php echo $personnel['realname'];?></td>
        <td><?php echo $personnel['projects'];?></td>
        <td><?php echo $personnel['executions'];?></td>
        <td><?php echo round($personnel['consumedTask'], 1);?></td>
        <td><?php echo round($personnel['leftTask'], 1);?></td>
        <td><?php echo $personnel['createdTask'];?></td>
        <td><?php echo $personnel['finishedTask'];?></td>
        <td><?php echo $personnel['pendingTask'];?></td>
        <td><?php echo $personnel['createdBug'];?></td>
        <td><?php echo $personnel['resolvedBug'];?></td>
        <td><?php echo $personnel['pendingBug'];?></td>
        <?php if($this->config->URAndSR):?>
        <td><?php echo $personnel['UR'];?></td>
        <?php endif;?>
        <td><?php echo $personnel['SR'];?></td>
        <?php if($this->config->edition == 'max' or $this->config->edition == 'ipd'): ?>
        <td><?php echo $personnel['createdIssue'];?></td>
        <td><?php echo $personnel['resolvedIssue'];?></td>
        <td><?php echo $personnel['pendingIssue'];?></td>
        <td><?php echo $personnel['createdRisk'];?></td>
        <td><?php echo $personnel['resolvedRisk'];?></td>
        <td><?php echo $personnel['pendingRisk'];?></td>
        <?php endif;?>
      </tr>
    <?php endforeach;?>
    <?php endforeach;?>
    </tbody>
  </table>
  <?php else:?>
  <div class="text-center px-2 py-8">
    <p>
      <span class="text-muted"><?php echo $lang->noData;?></span>
    </p>
  </div>
  <?php endif;?>
</div>
<?php
rawContent();
render();
