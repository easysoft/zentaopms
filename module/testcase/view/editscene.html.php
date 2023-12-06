<?php
/**
 * The create view of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: create.html.php 4904 2013-06-26 05:37:45Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('page', 'edit');?>
<?php js::set('isonlybody', isonlybody());?>
<?php js::set('executionID', $executionID);?>

<?php js::set('lblDelete', $lang->testcase->deleteStep);?>
<?php js::set('lblBefore', $lang->testcase->insertBefore);?>
<?php js::set('lblAfter',  $lang->testcase->insertAfter);?>
<?php js::set('sceneId',   $scene->id);?>
<?php js::set('caseBranch', $scene->branch);?>

<?php js::set('tab', $this->app->tab);?>
<?php if($this->app->tab == 'execution') js::set('objectID', $executionID);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <?php
    $showid = substr($scene->id, 1);
    $showid = preg_replace('/^0+/', '', $showid);
    ?>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $showid;?></span>
        <?php echo $scene->title;?>
      </h2>
    </div>

    <form method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tbody>
          <tr>
            <th><?php echo $lang->testcase->product;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::select('product', $products, $productID, "onchange='loadAllNew(this.value);' class='form-control chosen'");?>
                <?php if(isset($product->type) and $product->type != 'normal') echo html::select('branch', $branchTagOption, $scene->branch, "onchange='loadBranchNew();' class='form-control' style='width:120px'");?>
              </div>
            </td>
            <td style='padding-left:15px;'>
              <div class='input-group' id='moduleIdBox'>
                <span class="input-group-addon w-80px"><?php echo $lang->testcase->module?></span>
                <?php
                echo html::select('module', $moduleOptionMenu, $currentModuleID, "onchange='loadModuleRelatedNew();' class='form-control chosen'");
                if(count($moduleOptionMenu) == 1)
                {
                    echo "<span class='input-group-addon'>";
                    echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=case&currentModuleID=0&branch=$branch", '', true), $lang->tree->manage, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");
                    echo html::a("javascript:void(0)", $lang->refresh, '', "class='refresh' onclick='loadProductModulesNew($productID)'");
                    echo '</span>';
                }
                ?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->parentScene;?></th>
            <td colspan='2'>
              <div class='input-group' id='sceneIdBox'>
                <?php echo html::select('parent', $sceneOptionMenu, $currentParentID, "class='form-control chosen'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->sceneTitle;?></th>
            <td class="required" colspan='2'>
              <div class="input-group title-group">
                <div class="input-control has-icon-right">
                  <?php echo html::input('title',$scene->title, "class='form-control'");?>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='3' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo $gobackLink ? html::a($gobackLink, $lang->goback, '', 'class="btn btn-wide"') : html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<?php js::set('caseModule', $lang->testcase->module)?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
