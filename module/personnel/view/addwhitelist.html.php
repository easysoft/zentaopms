<?php
/**
 * The create addwhitelist view of personnel module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     personnel
 * @version     $Id
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('objectID', $objectID);?>
<?php js::set('objectType', $objectType);?>
<?php js::set('projectCommon', $lang->projectCommon);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'>
      <?php echo html::a("javascript:void(0)", "<span class='text'> {$lang->personnel->addWhitelist}</span>");?>
    </span>
    <div class='input-group space w-200px'>
      <span class='input-group-addon'><?php echo $lang->execution->selectDept;?></span>
      <?php echo html::select('dept', $depts, $deptID, "class='form-control chosen' onchange='setObjectUsers()' data-placeholder='{$lang->execution->selectDeptTitle}'");?>
      <span class='input-group-addon'><?php echo $lang->personnel->copy;?></span>
      <?php echo html::select('object', $objects, $copyID, "class='form-control chosen' onchange='setObjectUsers()' data-placeholder='{$lang->personnel->selectObjectTips}'");?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <form class='main-form form-ajax' method='post' target='hiddenwin' id="whitelistForm">
    <table class='table table-form'>
      <thead>
        <tr class='text-center'>
          <th><?php echo $lang->team->account;?></th>
          <th class="w-90px"> <?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1;?>
        <?php $whitelistUsers = array();?>
        <?php foreach($whitelist as $user):?>
        <tr id="whitelist<?php echo $i;?>" data-id="<?php echo $i;?>">
          <td>
            <input type='text' name='realnames[]' value='<?php echo $user->realname;?>' readonly class='form-control' />
            <input type='hidden' name='accounts[]' value='<?php echo $user->account;?>' />
          </td>
          <td class='c-actions text-center'>
            <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
            <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
          </td>
        </tr>
        <?php
            $i++;
            $whitelistUsers[$user->account] = $user->realname;
        ?>
        <?php endforeach;?>

        <?php foreach($appendUsers as $account => $realname):?>
        <?php if(isset($whitelistUsers[$account])) continue;?>
        <tr id="whitelist<?php echo $i;?>" data-id="<?php echo $i;?>">
          <td>
            <?php echo html::select('accounts[]', $users, $account, "class='form-control chosen'");?>
          </td>
          <td class='c-actions text-center'>
            <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
            <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
          </td>
        </tr>
        <?php $i++;?>
        <?php endforeach;?>

        <?php for($j = 0; $j < 5; $j ++):?>
        <tr id="whitelist<?php echo $i;?>" data-id="<?php echo $i;?>">
          <td><?php echo html::select('accounts[]', $users, '', "class='form-control chosen'");?></td>
          <td class='c-actions text-center'>
            <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
            <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
          </td>
        </tr>
        <?php $i++;?>
        <?php endfor;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='6' class='text-left form-actions'>
            <?php echo html::submitButton(); ?>
            <?php $gobackURL = $this->session->whitelistList;?>
            <?php $dataGroup = $module == 'program' ? ($from == 'project' || $from == 'my' ? "data-group='project'" : "data-group='program'") : '';?>
            <?php echo empty($gobackURL) ? html::backButton() : html::a($gobackURL, $lang->goback, '', "class='btn btn-back btn-wide' $dataGroup");?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<div>
  <table class='hidden'>
    <tr id='addItem' class='hidden'>
      <td><?php echo html::select("accounts[]", $users, '', "class='form-control'");?></td>
      <td class='c-actions text-center'>
        <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
        <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
      </td>
    </tr>
  </table>
</div>
<?php js::set('index', $i);?>
<?php js::set('module', $module);?>
<?php js::set('moduleMethod', 'addWhitelist');?>
<?php js::set('programID', $programID);?>
<?php js::set('from', $from);?>
<?php include '../../common/view/footer.html.php';?>
