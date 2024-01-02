<?php
/**
 * The importfromlib view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testcase
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('flow', $config->global->flow);?>
<?php js::set('ditto', $lang->testcase->ditto);?>
<?php js::set('canImportModules', $canImportModules);?>
<?php js::set('rawModule', $this->app->rawModule);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <div class='input-group w-300px'>
      <span class='input-group-addon'><?php echo $lang->testcase->selectLib;?></span>
      <?php echo html::select('fromlib', $libraries, $libID, "onchange='reload(this.value)' class='form-control chosen'");?>
    </div>
  </div>
</div>
<div id='queryBox' data-module='testsuite' class='show cell'></div>
<div id='mainContent'>
  <form class='main-table' method='post' target='hiddenwin' id='importFromLib' data-ride='table'>
    <table class='table has-sort-head table-fixed'>
      <thead>
        <?php $vars = "productID=$productID&branch=$branch&libID=$libID&orderBy=%s&browseType=$browseType&queryID=$queryID&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
        <tr>
          <th class='c-id'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <?php if(!$toLib && $product->type != 'normal'):?>
          <th class='c-branch'><?php echo $lang->testcase->branch ?></th>
          <?php endif;?>
          <th class='c-pri' title=<?php echo $lang->pri;?>><?php common::printOrderLink('pri',   $orderBy, $vars, $lang->priAB);?></th>
          <th><?php common::printOrderLink('title', $orderBy, $vars, $lang->testcase->title);?></th>
          <th class='c-module'><?php echo $lang->testcase->fromModule ?></th>
          <th class='c-module'><?php echo $lang->testcase->module ?></th>
          <th class='c-type'><?php common::printOrderLink('type',  $orderBy, $vars, $lang->testcase->type)?></th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 0;?>
        <?php foreach($cases as $id => $case):?>
        <?php
        if(!$toLib)
        {
            $caseBranches = $branches;
            $caseBranch   = ($branch == 'all' or empty($branch)) ? 0 : $branch;
            foreach($caseBranches as $branchID => $branchName)
            {
                if(empty($canImportModules[$branchID][$case->id]))
                {
                    unset($caseBranches[$branchID]);
                    if($caseBranch == $branchID) $caseBranch = key($caseBranches);
                }
            }
        }
        else
        {
            $caseBranch = 0;
        }
        ?>
        <tr id='<?php echo $case->id;?>'>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='caseIdList[<?php echo $case->id?>]' value='<?php echo $case->id;?>' />
              <label></label>
            </div>
            <?php printf('%03d', $case->id);?>
          </td>
          <?php if(!$toLib && $product->type != 'normal'):?>
          <td><?php echo html::select("branch[{$case->id}]", $caseBranches, $caseBranch, "class='form-control' onchange='updateModules($productID, this.value, $case->id)'")?></td>
          <?php endif;?>
          <td><span class='label-pri <?php echo 'label-pri-' . $case->pri;?>' title='<?php echo zget($lang->testcase->priList, $case->pri, $case->pri);?>'><?php echo $case->pri == '0' ? '' : zget($lang->testcase->priList, $case->pri, $case->pri);?></span></td>
          <td class='text-left nobr'><?php if(!common::printLink('testcase', 'view', "caseID=$case->id", $case->title, '', 'class="iframe" data-width="80%"', true, true)) echo $case->title;?></td>
          <?php $libModule = zget($libModules, $case->module, '');?>
          <td class='text-left' title='<?php echo $libModule?>'><?php echo $libModule;?></td>
          <td class='text-left' data-module='<?php echo $case->module?>' style='overflow:visible'>
            <?php if($i == 0) unset($canImportModules[$caseBranch][$case->id]['ditto']);?>
            <?php echo html::select("module[{$case->id}]", isset($canImportModules[$caseBranch][$case->id]) ? $canImportModules[$caseBranch][$case->id] : array(), $i == 0 ? 0 : 'ditto', "class='form-control chosen'");?>
          </td>
          <td><?php echo zget($lang->testcase->typeList, $case->type);?></td>
        </tr>
        <?php $i++;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($cases):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class='table-actions btn-toolbar show-always'>
        <?php echo html::submitButton($lang->testcase->import, '', 'btn btn-secondary');?>
      </div>
      <div class="btn-toolbar">
        <?php echo html::linkButton($lang->goback, $this->session->caseList);?>
      </div>
      <div class='table-statistic'></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php js::set('productID', $productID)?>
<?php js::set('branch', $branch)?>
<?php js::set('app', $app->tab)?>
<?php include '../../common/view/footer.html.php';?>
