<?php
/**
 * The test view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: test.html.php 1191 2010-11-13 07:30:35Z jajacn@126.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include './featurebar.html.php';?>
<div id='mainContent'>
  <nav id='contentNav'>
    <ul class='nav nav-default'>
      <?php
      $that = zget($lang->user->thirdPerson, $user->gender);

      $active = $type == 'case2Him' ? 'active' : '';
      echo "<li class='$active'>" . html::a($this->createLink('user', 'testcase', "userID={$user->id}&type=case2Him"),  sprintf($lang->user->case2Him, $that)) . "</li>";
      $active = $type == 'caseByHim' ? 'active' : '';
      echo "<li class='$active'>" . html::a($this->createLink('user', 'testcase', "userID={$user->id}&type=caseByHim"), sprintf($lang->user->caseByHim, $that)) . "</li>";
      ?>
    </ul>
  </nav>

  <div class='main-table'>
    <table class='table has-sort-head'>
      <?php
      $vars = "userID={$user->id}&type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID";
      $this->app->loadLang('testtask');
      ?>
      <thead>
        <tr class='colhead'>
          <th class='c-id'>    <?php common::printOrderLink('id',            $orderBy, $vars, $lang->idAB);?></th>
          <th>                 <?php common::printOrderLink('title',         $orderBy, $vars, $lang->testcase->title);?></th>
          <th class='c-pri'>   <?php common::printOrderLink('pri',           $orderBy, $vars, $lang->priAB);?></th>
          <th class='c-type'>  <?php common::printOrderLink('type',          $orderBy, $vars, $lang->testcase->type);?></th>
          <th class='c-status'><?php common::printOrderLink('status',        $orderBy, $vars, $lang->statusAB);?></th>
          <th class='c-user'>  <?php common::printOrderLink('openedBy',      $orderBy, $vars, $lang->testcase->openedByAB);?></th>
          <th class='c-user'>  <?php common::printOrderLink('lastRunner',    $orderBy, $vars, $lang->testtask->lastRunAccount);?></th>
          <th class='c-date'>  <?php common::printOrderLink('lastRunDate',   $orderBy, $vars, $lang->testtask->lastRunTime);?></th>
          <th class='c-result'><?php common::printOrderLink('lastRunResult', $orderBy, $vars, $lang->testtask->lastRunResult);?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($cases as $case):?>
        <?php $caseID = $type == 'case2Him' ? $case->case : $case->id?>
        <tr class='text-left'>
          <td><?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$caseID&version=$case->version"), sprintf('%03d', $caseID), '', "data-app='qa'");?></td>
          <td class='text-left'><?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$caseID&version=$case->version"), $case->title, '', "data-app='qa'");?></td>
          <td class='c-pri'><span class='<?php if($case->pri) echo 'label-pri label-pri-' . zget($lang->testcase->priList, $case->pri, $case->pri)?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri)?></span></td>
          <td><?php echo $lang->testcase->typeList[$case->type];?></td>
          <td class='status-testcase status-<?php echo $case->status;?>'>
            <?php
            if($case->needconfirm)
            {
                print("<span class='status-story status-changed' title='{$this->lang->story->changed}'>{$this->lang->story->changed}</span>");
            }
            elseif(isset($case->fromCaseVersion) and $case->fromCaseVersion > $case->version and !$case->needconfirm)
            {
                print("<span class='status-story status-changed' title='{$this->lang->testcase->changed}'>{$this->lang->testcase->changed}</span>");
            }
            else
            {
                print("<span class='status-testcase status-{$case->status}'>" . $this->processStatus('testcase', $case) . "</span>");
            }
            ?>
          </td>
          <td><?php echo zget($users, $case->openedBy);?></td>
          <td><?php echo zget($users, $case->lastRunner);?></td>
          <td><?php echo helper::isZeroDate($case->lastRunDate) ? '' : substr($case->lastRunDate, 5, 11);?></td>
          <td class='result-testcase <?php echo $case->lastRunResult;?>'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($cases):?>
    <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
