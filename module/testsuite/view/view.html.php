<?php
/**
 * The view file of testsuite module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testsuite
 * @version     $Id: view.html.php 4141 2013-01-18 06:15:13Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmUnlink', $lang->testsuite->confirmUnlinkCase)?>
<?php js::set('flow', $config->global->flow);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php $browseLink = $this->session->testsuiteList ? $this->session->testsuiteList : $this->createLink('testsuite', 'browse', "productID=$suite->product");?>
    <?php common::printBack($browseLink, 'btn btn-primary');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='label label-id'><?php echo $suite->id;?></span>
      <span class='text'><?php echo $suite->name;?></span>
      <?php if($suite->deleted):?>
      <span class='label label-danger'><?php echo $lang->delete;?></span>
      <?php endif; ?>
    </div>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php
    if(!$suite->deleted)
    {
        echo $this->buildOperateMenu($suite, 'view');

        common::printIcon('testsuite', 'linkCase', "suiteID=$suite->id", $suite, 'button', 'link');
        common::printIcon('testsuite', 'edit',     "suiteID=$suite->id");
        common::printIcon('testsuite', 'delete',   "suiteID=$suite->id", '', 'button', 'trash', 'hiddenwin');
    }
    ?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-col col-8'>
    <div class='main'>
      <form class='main-table table-case' data-ride='table' method='post' name='casesform' id='casesForm'>
      <div class='panel'>
        <?php $canBatchEdit   = common::hasPriv('testcase', 'batchEdit');?>
        <?php $canBatchUnlink = common::hasPriv('testsuite', 'batchUnlinkCases');?>
        <?php $hasCheckbox    = ($canBatchEdit && $canBatchUnlink);?>
        <?php $vars = "suiteID=$suite->id&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
        <table class='table has-sort-head' id='caseList'>
          <thead>
            <tr class='colhead'>
              <th class='c-id'>
                <?php if($hasCheckbox):?>
                <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                  <label></label>
                </div>
                <?php endif;?>
                <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></nobr>
              </th>
              <th class='c-pri'>   <?php common::printOrderLink('pri',           $orderBy, $vars, $lang->priAB);?></th>
              <th class='w-150px'> <?php common::printOrderLink('module',        $orderBy, $vars, $lang->testcase->module);?></th>
              <th>                 <?php common::printOrderLink('title',         $orderBy, $vars, $lang->testcase->title);?></th>
              <th class='w-90px'>  <?php common::printOrderLink('type',          $orderBy, $vars, $lang->testcase->type);?></th>
              <th class='c-status'><?php common::printOrderLink('status',        $orderBy, $vars, $lang->statusAB);?></th>
              <th class='w-80px'>  <?php common::printOrderLink('lastRunResult', $orderBy, $vars, $lang->testcase->lastRunResult);?></th>
              <th class='w-30px' title='<?php echo $lang->testcase->bugs?>'><?php echo $lang->testcase->bugsAB;?></th>
              <th class='w-30px' title='<?php echo $lang->testcase->results?>'><?php echo $lang->testcase->resultsAB;?></th>
              <th class='w-30px' title='<?php echo $lang->testcase->stepNumber?>'><?php echo $lang->testcase->stepNumberAB;?></th>
              <th class='c-actions-3 text-center'><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <?php if($cases):?>
          <tbody>
            <?php foreach($cases as $case):?>
            <tr>
              <td class='c-id'>
                <?php if($hasCheckbox):?>
                <?php echo html::checkbox('caseIDList', array($case->id => sprintf('%03d', $case->id)));?>
                <?php else:?>
                <?php printf('%03d', $case->id);?>
                <?php endif;?>
              </td>
              <td><span class='label-pri label-pri-<?php echo $case->pri;?>' title='<?php echo zget($lang->testcase->priList, $case->pri, $case->pri);?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri)?></span></td>
              <td class='text-left' title='<?php echo $modules[$case->module]?>'><?php echo $modules[$case->module];?></td>
              <td class='text-left nobr' title='<?php echo $case->title;?>'>
                <?php if($case->branch) echo "<span class='label label-info label-badge'>{$branches[$case->branch]}</span>"?>
                <?php echo html::a($this->createLink('testcase', 'view', "caseID=$case->id&version=$case->caseVersion"), $case->title);?>
              </td>
              <td><?php echo $lang->testcase->typeList[$case->type];?></td>
              <td class='<?php echo $case->status;?>'><?php echo ($case->version < $case->caseVersion) ? "<span class='warning'>{$lang->testcase->changed}</span>" : $this->processStatus('testcase', $case);?></td>
              <td class='<?php echo $case->lastRunResult;?>'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
              <td><?php echo (common::hasPriv('testcase', 'bugs') and $case->bugs) ? html::a($this->createLink('testcase', 'bugs', "runID=0&caseID={$case->id}"), $case->bugs, '', "class='iframe'") : $case->bugs;?></td>
              <td><?php echo (common::hasPriv('testtask', 'results') and $case->results) ? html::a($this->createLink('testtask', 'results', "runID=0&caseID={$case->id}"), $case->results, '', "class='iframe'") : $case->results;?></td>
              <td><?php echo $case->stepNumber;?></td>
              <td class='c-actions'>
                <?php
                if(common::hasPriv('testsuite', 'unlinkCase', $suite))
                {
                    $unlinkURL = $this->createLink('testsuite', 'unlinkCase', "suiteID=$suite->id&caseID=$case->id&confirm=yes");
                    echo html::a("javascript:ajaxDelete(\"$unlinkURL\", \"caseList\", confirmUnlink)", '<i class="icon-unlink"></i>', '', "title='{$lang->testsuite->unlinkCase}' class='btn'");
                }
                if(common::hasPriv('testtask', 'runCase')) echo html::a($this->createLink('testtask', 'runCase', "runID=0&caseID=$case->id&version=$case->version"), '<i class="icon-testtask-runCase icon-play"></i>', '', "class='btn runCase iframe' data-width='95%' title={$lang->testtask->runCase}");
                common::printIcon('testtask', 'results', "runID=0&caseID=$case->id", $suite, 'list', 'list-alt', '', 'results iframe', false, "data-width='95%'");
                ?>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
          <?php endif;?>
        </table>
        <div class='table-footer'>
          <?php if($cases):?>
          <?php if($hasCheckbox):?>
          <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
          <?php endif;?>
          <div class='table-actions btn-toolbar'>
            <div class='btn-group dropup'>
              <?php
              $actionLink = $this->createLink('testcase', 'batchEdit', "productID=$productID");
              $misc       = $canBatchEdit ? "onclick=\"setFormAction('$actionLink')\"" : "disabled='disabled'";
              echo html::commonButton($lang->edit, $misc);
              ?>
              <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
              <ul class='dropdown-menu'>
                <?php
                $actionLink = $this->createLink('testsuite', 'batchUnlinkCases', "suiteID=$suite->id");
                $misc       = common::hasPriv('testsuite', 'batchUnlinkCases') ? "onclick=\"setFormAction('$actionLink')\"" : "class='disabled'";
                echo "<li>" . html::a('javascript:;', $lang->testsuite->unlinkCase, '', $misc) . "</li>";

                $actionLink = $this->createLink('testtask', 'batchRun', "productID=$productID&orderBy=$orderBy");
                $misc = common::hasPriv('testtask', 'batchRun') ? "onclick=\"setFormAction('$actionLink')\"" : $class;
                echo "<li>" . html::a('#', $lang->testtask->runCase, '', $misc) . "</li>";
                ?>
              </ul>
            </div>
          </div>
          <?php endif;?>
          <?php $pager->show('right', 'pagerjs');?>
        </div>
      </div>
      </form>
    </div>
  </div>
  <div class='side-col col-4'>
    <div class='cell'>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testsuite->legendDesc;?></div>
        <div class='detail-content article-content'>
          <?php echo $suite->desc;?>
        </div>
      </div>
    </div>
    <?php $this->printExtendFields($suite, 'div', "position=right&inForm=0&inCell=1");?>
    <div class='cell'>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
