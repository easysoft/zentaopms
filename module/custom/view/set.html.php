<?php
/**
 * The set view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php
$itemRow = <<<EOT
  <tr class='text-center'>
    <td>
      <input type='text' class="form-control" autocomplete="off" value="" name="keys[]">
      <input type='hidden' value="0" name="systems[]">
    </td>
    <td>
      <input type='text' class="form-control" value="" autocomplete="off" name="values[]">
    </td>
    <td class='c-actions text-left'>
      <a href="javascript:void(0)" class='btn btn-link' onclick="addItem(this)"><i class='icon-plus'></i></a>
      <a href="javascript:void(0)" class='btn btn-link' onclick="delItem(this)"><i class='icon-close'></i></a>
    </td>
  </tr>
EOT;
?>
<style>
.checkbox-primary {width: 170px; margin: 0 10px 10px 0; display: inline-block;}
</style>
<?php js::set('itemRow', $itemRow)?>
<?php js::set('module',  $module)?>
<?php js::set('field',   $field)?>
<?php js::set('confirmReviewCase', $lang->custom->notice->confirmReviewCase)?>
<?php js::set('stopSubmit', true)?>
<?php if(($module == 'story' or $module == 'demand') and $field == 'review'):?>
<style>
.table-form>tbody>tr>th {width: 120px !important}
.checkbox-primary {margin-bottom: 0px; width: 82px !important;}
.storyReviewTip  > div , .storyNotReviewTip  > div {padding-left: 120px;}
<?php if($app->getClientLang() != 'zh-cn' and $app->getClientLang() != 'zh-tw'):?>
.table-form>tbody>tr>th {width: 180px !important;}
.storyReviewTip  > div , .storyNotReviewTip  > div {padding-left: 180px;}
<?php endif;?>
</style>
<?php endif;?>
<div id='mainContent' class='main-row'>
  <?php if(!in_array($module, array('block', 'baseline'))) include 'sidebar.html.php';?>
  <div class='main-col main-content'>
    <form class="load-indicator main-form form-ajax" method='post'>
      <div class='main-header'>
        <div class='heading'>
          <strong><?php echo $lang->custom->$module->fields[$field]?></strong>
        </div>
      </div>
      <?php if($module == 'project' and $field == 'unitList'):?>
      <table class='table table-form'>
        <tr>
          <th class='<?php echo strpos($this->app->getClientLang(), 'zh') === false ? 'w-120px' : 'w-70px';?> text-left'><?php echo $lang->custom->project->currencySetting;?></th>
        </tr>
        <tr>
          <td colspan='5'><?php echo html::checkbox('unitList', $lang->project->unitList, $unitList);?></td>
        </tr>
        <tr>
          <th class='text-left'><?php echo $lang->custom->project->defaultCurrency;?></th>
          <td><?php echo html::select('defaultCurrency', $lang->project->unitList, $defaultCurrency, "class='form-control chosen' required");?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center'><?php echo html::submitButton();?></td>
        </tr>
      </table>
      <?php elseif(in_array($module, array('story', 'demand')) and $field == 'reviewRules'):?>
      <table class='table table-form mw-700px'>
        <tr>
          <th class='thWidth'><?php echo $lang->custom->reviewRule;?></th>
          <td><?php echo html::radio('reviewRules', $lang->custom->reviewRules, $reviewRule);?></td>
          <td></td>
        </tr>
        <tr>
          <th class="thWidth"><?php echo $lang->custom->superReviewers;?></th>
          <td><?php echo html::select('superReviewers[]', $users, $superReviewers, "class='form-control picker-select' multiple");?></td>
        </tr>
        <tr>
          <td colspan='2' class='text-center'><?php echo html::submitButton();?></td>
        </tr>
      </table>
      <?php elseif(in_array($module, array('story', 'testcase', 'demand')) and $field == 'review'):?>
      <table class='table table-form'>
        <tr class='reviewBox'>
          <th class='thWidth'><?php echo $lang->custom->storyReview;?></th>
          <td><?php echo html::radio('needReview', $lang->custom->reviewList, $needReview);?></td>
        </tr>
        <?php if($module == 'story' or $module == 'demand'):?>
        <tr>
          <?php $space = ($app->getClientLang() != 'zh-cn' and $app->getClientLang() != 'zh-tw') ? ' ': '';?>
          <td colspan='3' class='storyReviewTip<?php if($needReview) echo " hidden"?>'><div><?php echo sprintf($lang->custom->notice->forceReview, $lang->$module->common) . $lang->custom->notice->storyReviewTip;?></td>
          <td colspan='3' class='storyNotReviewTip<?php if(!$needReview) echo " hidden"?>'><div><?php echo sprintf($lang->custom->notice->forceNotReview, $lang->$module->common) . $lang->custom->notice->storyReviewTip;?></td>
        </tr>
        <tr id='userBox' class='forceReview<?php if($needReview) echo " hidden"?>'>
          <th><?php echo $lang->custom->forceReview . $space . $lang->custom->account;?></th>
          <td><?php echo html::select('forceReview[]', $users, $forceReview, "class='form-control picker-select' multiple");?></td>
        </tr>
        <tr id='roleBox' class='forceReview<?php if($needReview) echo " hidden"?>'>
          <th><?php echo $lang->custom->forceReview . $space . $lang->custom->role;?></th>
          <td><?php echo html::select('forceReviewRoles[]', $lang->user->roleList, $forceReviewRoles, "class='form-control picker-select' multiple");?></td>
        </tr>
        <tr id='deptBox' class='forceReview<?php if($needReview) echo " hidden"?>'>
          <th><?php echo $lang->custom->forceReview . $space . $lang->custom->dept;?></th>
          <td><?php echo html::select('forceReviewDepts[]', $depts, $forceReviewDepts, "class='form-control picker-select' multiple");?></td>
        </tr>
        <tr id='userBox' class='forceNotReview<?php if(!$needReview) echo " hidden"?>'>
          <th><?php echo $lang->custom->forceNotReview . $space . $lang->custom->account;?></th>
          <td><?php echo html::select('forceNotReview[]', $users, $forceNotReview, "class='form-control picker-select' multiple");?></td>
        </tr>
        <tr id='roleBox' class='forceNotReview<?php if(!$needReview) echo " hidden"?>'>
          <th><?php echo $lang->custom->forceNotReview . $space . $lang->custom->role;?></th>
          <td><?php echo html::select('forceNotReviewRoles[]', $lang->user->roleList, $forceNotReviewRoles, "class='form-control picker-select' multiple");?></td>
        </tr>
        <tr id='deptBox' class='forceNotReview<?php if(!$needReview) echo " hidden"?>'>
          <th><?php echo $lang->custom->forceNotReview . $space . $lang->custom->dept;?></th>
          <td><?php echo html::select('forceNotReviewDepts[]', $depts, $forceNotReviewDepts, "class='form-control picker-select' multiple");?></td>
        </tr>
        <?php endif;?>
        <?php if($module == 'testcase'):?>
        <?php js::set('oldNeedReview', $needReview);?>
        <tr <?php if($needReview) echo "class='hidden'"?>>
          <th><?php echo $lang->custom->forceReview;?></th>
          <td><?php echo html::select('forceReview[]', $users, $forceReview, "class='form-control picker-select' multiple");?></td>
          <td style='width:300px'><?php printf($lang->custom->notice->forceReview, $lang->$module->common);?></td>
        </tr>
        <tr <?php if(!$needReview) echo "class='hidden'"?>>
          <th><?php echo $lang->custom->forceNotReview;?></th>
          <td><?php echo html::select('forceNotReview[]', $users, $forceNotReview, "class='form-control picker-select' multiple");?></td>
          <td style='width:300px'><?php printf($lang->custom->notice->forceNotReview, $lang->$module->common);?></td>
        </tr>
        <?php endif;?>
        <tr>
          <td colspan='2' class='text-center'><?php echo html::submitButton();?></td>
        </tr>
      </table>
      <?php elseif($module == 'bug' and $field == 'longlife'):?>
      <table class='table table-form mw-600px'>
        <tr>
          <th class='w-100px'><?php echo $lang->custom->bug->fields['longlife'];?></th>
          <td class='w-100px'>
            <div class='input-group'>
              <?php echo html::input('longlife', $longlife, "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->day?></span>
            </div>
          </td>
          <td><?php echo html::submitButton();?></td>
        </tr>
      </table>
      <div class='alert alert-info alert-block'><?php echo $lang->custom->notice->longlife;?></div>
      <?php elseif($module == 'block' and $field == 'closed'):?>
      <table class='table table-form mw-600px'>
        <tr>
          <th class='w-100px'><?php echo $lang->custom->block->fields['closed'];?></th>
          <td>
            <?php
            if(empty($blockPairs))
            {
                echo $lang->custom->notice->noClosedBlock;
            }
            else
            {
                echo html::select('closed[]', $blockPairs, $closedBlock, "class='form-control chosen' multiple");
            }
            ?>
          </td>
        </tr>
        <tr>
          <?php if(!empty($blockPairs)):?>
          <td colspan='2' class='text-center'><?php echo html::submitButton();?></td>
          <?php endif;?>
        </tr>
      </table>
      <?php elseif($module == 'user' and $field == 'contactField'):?>
      <?php
      $this->app->loadConfig('user');
      $this->app->loadLang('user');
      ?>
      <table class='table table-form mw-800px'>
        <tr>
          <th class='w-150px'><?php echo $lang->custom->user->fields['contactField'];?></th>
          <td><?php echo html::select('contactField[]', $lang->user->contactFieldList, $config->user->contactField, "class='form-control picker-select' multiple");?></td>
        </tr>
        <tr>
          <td></td>
          <td class="form-actions">
            <?php echo html::submitButton();?>
            <?php if(common::hasPriv('custom', 'restore')) echo html::linkButton($lang->custom->restore, inlink('restore', "module=user&field=contactField"), 'hiddenwin', '', 'btn btn-wide');?>
          </td>
        </tr>
      </table>
      <?php elseif($module == 'user' and $field == 'deleted'):?>
      <table class='table table-form mw-600px'>
        <tr>
          <th class='w-150px'><?php echo $lang->custom->user->fields['deleted'];?></th>
          <td><?php echo html::radio('showDeleted', $lang->custom->deletedList, $showDeleted);?></td>
        </tr>
        <tr>
          <td></td>
          <td><?php echo html::submitButton();?></td>
        </tr>
      </table>
      <?php else:?>
      <?php if(!empty($fieldList) && is_array($fieldList)):?>
      <table class='table table-form active-disabled table-condensed mw-600px'>
        <tr class='text-center'>
          <td class='w-120px'><strong><?php echo $lang->custom->key;?></strong></td>
          <td><strong><?php echo $lang->custom->value;?></strong></td>
          <?php if($canAdd):?><th class='w-90px'></th><?php endif;?>
        </tr>
        <?php foreach($fieldList as $key => $value):?>
        <tr class='text-center'>
          <?php $system = isset($dbFields[$key]) ? $dbFields[$key]->system : 1;?>
          <td><?php echo $key === '' ? 'NULL' : $key; echo html::hidden('keys[]', $key) . html::hidden('systems[]', $system);?></td>
          <td>
            <?php echo html::input("values[]", isset($dbFields[$key]) ? $dbFields[$key]->value : $value, "class='form-control' " . (empty($key) ? 'readonly' : ''));?>
          </td>
          <?php if($canAdd):?>
          <td class='c-actions text-left'>
            <a href="javascript:void(0)" onclick="addItem(this)" class='btn btn-link'><i class='icon-plus'></i></a>
            <a href="javascript:void(0)" onclick="delItem(this)" class='btn btn-link'><i class='icon-close'></i></a>
          </td>
          <?php endif;?>
        </tr>
        <?php endforeach;?>
        <tr>
          <td colspan='<?php $canAdd ? print(3) : print(2);?>' class='text-center form-actions'>
          <?php
          $appliedTo = array($currentLang => $lang->custom->currentLang, 'all' => $lang->custom->allLang);
          echo html::radio('lang', $appliedTo, $lang2Set);
          echo html::submitButton();
          if(common::hasPriv('custom', 'restore')) echo html::linkButton($lang->custom->restore, inlink('restore', "module=$module&field=$field"), 'hiddenwin', '', 'btn btn-wide');
          ?>
          </td>
        </tr>
      </table>
      <?php if(!$canAdd):?>
      <div class='alert alert-warning alert-block'><?php echo $lang->custom->notice->canNotAdd;?></div>
      <?php endif;?>
      <?php endif;?>
      <?php endif;?>
    </form>
  </div>
</div>
<?php if(in_array($module, array('story', 'demand')) and $field == 'review'):?>
<script>
$(function()
{
    $('[data-toggle="popover"]').popover();

    $("input[name='needReview']").change(function()
    {
        needReviewChange();
    })

    /**
     * When needReview change.
     *
     * @access public
     * @return void
     */
    function needReviewChange()
    {
        var needReview = $("input[name='needReview']:checked").val();
        if(needReview == 1)
        {
            $('.storyReviewTip').addClass('hidden');
            $('.forceReview').addClass('hidden');
            $('.storyNotReviewTip').removeClass('hidden');
            $('.forceNotReview').removeClass('hidden');
        }
        else
        {
            $('.forceReview').removeClass('hidden');
            $('.storyReviewTip').removeClass('hidden');
            $('.forceNotReview').addClass('hidden');
            $('.storyNotReviewTip').addClass('hidden');
        }
    }
})
</script>
<?php endif;?>
<?php if($module == 'testcase' and $field == 'review'):?>
<script>
$(function()
{
    $("input[name='needReview']").change(function()
    {
        if($(this).val() == 0)
        {
            $('#forceReview').closest('tr').removeClass('hidden');
            $('#forceNotReview').closest('tr').addClass('hidden');
        }
        else
        {
            $('#forceReview').closest('tr').addClass('hidden');
            $('#forceNotReview').closest('tr').removeClass('hidden');
        }
    })
})
</script>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
