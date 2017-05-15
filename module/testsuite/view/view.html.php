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
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix' title='TESTSUITE'><strong><?php echo $suite->id;?></strong></span>
    <strong><?php echo $suite->name;?></strong>
    <?php if($suite->deleted):?>
    <span class='label label-danger'><?php echo $lang->suite->deleted;?></span>
    <?php endif; ?>
  </div>
  <div class='actions'>
    <?php
    $browseLink = $this->session->testsuiteList ? $this->session->testsuiteList : $this->createLink('testsuite', 'browse', "productID=$suite->product");
    $actionLinks = '';
    if(!$suite->deleted)
    {
        ob_start();

        echo "<div class='btn-group'>";
        common::printIcon('testsuite', 'linkCase', "suiteID=$suite->id", $suite, 'button', 'link');
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printIcon('testsuite', 'edit',     "suiteID=$suite->id");
        common::printIcon('testsuite', 'delete',   "suiteID=$suite->id", '', 'button', '', 'hiddenwin');
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printRPN($browseLink);
        echo '</div>';

        $actionLinks = ob_get_contents();
        ob_end_clean();
        echo $actionLinks;
    }
    ?>
  </div>
</div>
<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <form method='post' name='casesform' id='casesForm'>
      <div class='panel'>
        <div class='panel-heading'>
          <strong><?php echo $lang->testcase->common?></strong>
          <div class='panel-actions pull-right'><?php common::printIcon('testsuite', 'linkCase', "suiteID=$suite->id", $suite, 'button', 'link');?></div>
        </div>
        <?php $vars = "suiteID=$suite->id&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
        <table class='table table-condensed table-hover table-striped tablesorter table-fixed table-selectable' id='caseList'>
          <thead>
            <tr class='colhead'>
              <th class='w-id {sorter: false}'><nobr><?php common::printOrderLink('id',            $orderBy, $vars, $lang->idAB);?></nobr></th>
              <th class='w-pri {sorter: false}'>     <?php common::printOrderLink('pri',           $orderBy, $vars, $lang->priAB);?></th>
              <th class='w-150px {sorter: false}'>   <?php common::printOrderLink('module',        $orderBy, $vars, $lang->testcase->module);?></th>
              <th class='{sorter: false}'>           <?php common::printOrderLink('title',         $orderBy, $vars, $lang->testcase->title);?></th>
              <th class='w-type {sorter: false}'>    <?php common::printOrderLink('type',          $orderBy, $vars, $lang->testcase->type);?></th>
              <th class='w-status {sorter: false}'>  <?php common::printOrderLink('status',        $orderBy, $vars, $lang->statusAB);?></th>
              <th class='w-80px {sorter:false}'>     <?php common::printOrderLink('lastRunResult', $orderBy, $vars, $lang->testcase->lastRunResult);?></th>
              <th class='w-30px' title='<?php echo $lang->testcase->bugs?>'> <?php echo $lang->testcase->bugsAB;?></th>
              <th class='w-30px' title='<?php echo $lang->testcase->results?>'> <?php echo $lang->testcase->resultsAB;?></th>
              <th class='w-30px' title='<?php echo $lang->testcase->stepNumber?>'> <?php echo $lang->testcase->stepNumberAB;?></th>
              <th class='w-100px {sorter: false}'><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <?php
          $canBatchEdit   = common::hasPriv('testcase', 'batchEdit');
          $canBatchUnlink = common::hasPriv('testsuite', 'batchUnlinkCases');
          $hasCheckbox    = ($canBatchEdit && $canBatchUnlink);
          ?>
          <?php if($cases):?>
          <tbody>
            <?php foreach($cases as $case):?>
            <tr class='text-center'>
              <td class='cell-id'>
                <?php if($hasCheckbox):?>
                <input type='checkbox' name='caseIDList[]' value='<?php echo $case->id;?>'/> 
                <?php endif;?>
                <?php printf('%03d', $case->id);?>
              </td>
              <td><span class='<?php echo 'pri' . zget($lang->testcase->priList, $case->pri, $case->pri)?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri)?></span></td>
              <td class='text-left' title='<?php echo $modules[$case->module]?>'><?php echo $modules[$case->module];?></td>
              <td class='text-left nobr'>
                <?php if($case->branch) echo "<span class='label label-info label-badge'>{$branches[$case->branch]}</span>"?>
                <?php echo html::a($this->createLink('testcase', 'view', "caseID=$case->id&version=$case->caseVersion"), $case->title);?>
              </td>
              <td><?php echo $lang->testcase->typeList[$case->type];?></td>
              <td class='<?php echo $case->status;?>'><?php echo ($case->version < $case->caseVersion) ? "<span class='warning'>{$lang->testcase->changed}</span>" : $lang->testcase->statusList[$case->status];?></td>
              <td class='<?php echo $case->lastRunResult;?>'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
              <td><?php echo (common::hasPriv('testcase', 'bugs') and $case->bugs) ? html::a($this->createLink('testcase', 'bugs', "runID=0&caseID={$case->id}"), $case->bugs, '', "class='iframe'") : $case->bugs;?></td>
              <td><?php echo (common::hasPriv('testtask', 'results') and $case->results) ? html::a($this->createLink('testtask', 'results', "runID=0&caseID={$case->id}"), $case->results, '', "class='iframe'") : $case->results;?></td>
              <td><?php echo $case->stepNumber;?></td>
              <td class='text-center'>
                <?php
                if(common::hasPriv('testsuite', 'unlinkCase'))
                {
                    $unlinkURL = $this->createLink('testsuite', 'unlinkCase', "suiteID=$suite->id&caseID=$case->id&confirm=yes");
                    echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"caseList\",confirmUnlink)", '<i class="icon-unlink"></i>', '', "title='{$lang->testsuite->unlinkCase}' class='btn-icon'");
                }
                common::printIcon('testtask', 'runCase', "runID=0&caseID=$case->id&version=$case->version", '', 'list', 'play', '', 'runCase iframe', false, "data-width='95%'");
                common::printIcon('testtask', 'results', "runID=0&caseID=$case->id", '', 'list', 'list-alt', '', 'results iframe', false, "data-width='95%'");
                ?>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
          <?php endif;?>
          <tfoot>
            <tr>
              <td colspan='11' style='padding-left:5px;'>
                <?php if($cases):?>
                <div class='table-actions clearfix'>
                  <?php if($hasCheckbox) echo html::selectButton();?>
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
                <?php echo $pager->show();?>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
      </form>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->testsuite->legendDesc;?></legend>
        <div class='article-content'><?php echo $suite->desc;?></div>
      </fieldset>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
