<?php
/**
 * The link user view of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: managemembers.html.php 4662 2013-04-18 02:34:33Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('projectID', $project->id);?>
<?php js::set('team2Import', $team2Import);?>
<?php js::set('roles', $roles);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'>
      <?php echo html::a($this->createLink('project', 'managemembers', "projectID={$project->id}"), "<span class='text'> {$lang->project->manageMembers}</span>");?>
    </span>
    <div class='input-group space w-200px'>
      <span class='input-group-addon'><?php echo $lang->project->selectDept?></span>
      <?php echo html::select('dept', $depts, $dept, "class='form-control chosen' onchange='setDeptUsers(this)' data-placeholder='{$lang->project->selectDeptTitle}'");?>
      <?php if(count($teams2Import) != 1):?>
      <span class='input-group-addon'><?php echo $lang->project->copyTeam?></span>
      <?php echo html::select('project', $teams2Import, $team2Import, "class='form-control chosen' onchange='choseTeam2Copy(this)' data-placeholder='{$lang->project->copyTeamTitle}'");?>
      <?php endif;?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <form class='main-form' method='post' id='teamForm' target='hiddenwin'>
    <table class='table table-form'>
      <thead>
        <tr class='text-center'>
          <th><?php echo $lang->team->account;?></th>
          <th><?php echo $lang->team->role;?></th>
          <th class='w-100px'><?php echo $lang->team->days;?></th>
          <th class='w-100px'><?php echo $lang->team->hours;?></th>
          <th class='w-110px'><?php echo $lang->team->limited;?></th>
          <th class="w-90px"> <?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 0; $memberCount = 0;?>
        <?php foreach($currentMembers as $member):?>
        <?php if(!isset($users[$member->account])) continue;?>
        <?php unset($users[$member->account]);?>
        <tr>
          <td><input type='text' name='realnames[]' id='account<?php echo $i;?>' value='<?php echo $member->realname;?>' readonly class='form-control' /></td>
          <td><input type='text' name='roles[]'     id='role<?php echo $i;?>'    value='<?php echo $member->role;?>' class='form-control' /></td>
          <td><input type='text' name='days[] '     id='days<?php echo $i;?>'    value='<?php echo $member->days;?>' class='form-control' /></td>
          <td>
            <input type='text'   name='hours[]' id='hours<?php echo $i;?>' value='<?php echo $member->hours;?>' class='form-control' />
            <input type='hidden' name='accounts[]' value='<?php echo $member->account;?>' />
          </td>
          <td><?php echo html::radio("limited[$i]", $lang->team->limitedList, $member->limited);?></td>
          <td class='c-actions text-center'>
            <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
            <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
          </td>
        </tr>
        <?php $i ++; $memberCount ++;?>
        <?php endforeach;?>

        <?php foreach($members2Import as $member2Import):?>
        <tr class='addedItem'>
          <td><?php echo html::select("accounts[]", $users, $member2Import->account, "class='form-control chosen' onchange='setRole(this.value, $i)'");?></td>
          <td><input type='text' name='roles[]' id='role<?php echo $i;?>' class='form-control' value='<?php echo $member2Import->role;?>' /></td>
          <td><input type='text' name='days[]'  id='days<?php echo $i;?>' class='form-control' value='<?php echo $project->days?>'/></td>
          <td>
            <input type='text'   name='hours[]' id='hours<?php echo $i;?>' class='form-control' value='<?php echo $member2Import->hours;?>' />
          </td>
          <td><?php echo html::radio("limited[$i]", $lang->team->limitedList, 'no');?></td>
          <td class='c-actions text-center'>
            <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
            <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
          </td>
        </tr>
        <?php $i ++; $memberCount ++;?>
        <?php endforeach;?>

        <?php foreach($deptUsers as $deptAccount => $userName):?>
        <?php if(!isset($users[$deptAccount])) continue;?>
        <tr class='addedItem'>
          <td><?php echo html::select("accounts[]", $users, $deptAccount, "class='form-control chosen' onchange='setRole(this.value, $i)'");?></td>
          <td><input type='text' name='roles[]' id='role<?php echo $i;?>' class='form-control' value='<?php echo $roles[$deptAccount]?>'/></td>
          <td><input type='text' name='days[]'  id='days<?php echo $i;?>' class='form-control' value='<?php echo $project->days?>'/></td>
          <td>
            <input type='text'   name='hours[]' id='hours<?php echo $i;?>' class='form-control' value='<?php echo $config->project->defaultWorkhours?>' />
          </td>
          <td><?php echo html::radio("limited[$i]", $lang->team->limitedList, 'no');?></td>
          <td class='c-actions text-center'>
            <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
            <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
          </td>
        </tr>
        <?php unset($users[$deptAccount]);?>
        <?php $i ++; $memberCount ++;?>
        <?php endforeach;?>

        <?php for($j = 0; $j < 5; $j ++):?>
        <tr class='addedItem'>
          <td><?php echo html::select("accounts[]", $users, '', "class='form-control chosen' onchange='setRole(this.value, $i)'");?></td>
          <td><input type='text' name='roles[]' id='role<?php  echo ($i);?>' class='form-control' /></td>
          <td><input type='text' name='days[]'  id='days<?php  echo ($i);?>' class='form-control' value='<?php echo $project->days?>'/></td>
          <td>
            <input type='text'   name='hours[]' id='hours<?php echo ($i);?>' class='form-control' value='<?php echo $config->project->defaultWorkhours?>' />
          </td>
          <td><?php echo html::radio("limited[$i]", $lang->team->limitedList, 'no');?></td>
          <td class='c-actions text-center'>
            <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
            <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
          </td>
        </tr>
        <?php $i ++; $memberCount ++;?>
        <?php endfor;?>
      </tbody>
      <tfoot><tr><td colspan='6' class='text-center form-actions'><?php echo html::submitButton() . ' ' . html::backButton(); ?></td></tr></tfoot>
    </table>
    <?php js::set('i', $i);?>
  </form>
</div>
<div>
  <?php $i = '%i%';?>
  <table class='hidden'>
    <tr id='addItem' class='hidden'>
      <td><?php echo html::select("accounts[]", $users, '', "class='form-control' onchange='setRole(this.value, $i)'");?></td>
      <td><input type='text' name='roles[]' id='role<?php  echo ($i);?>' class='form-control' /></td>
      <td><input type='text' name='days[]'  id='days<?php  echo ($i);?>' class='form-control' value='<?php echo $project->days?>'/></td>
      <td>
        <input type='text'   name='hours[]' id='hours<?php echo ($i);?>' class='form-control' value='<?php echo $config->project->defaultWorkhours?>' />
      </td>
      <td><?php echo html::radio("limited[$i]", $lang->team->limitedList, 'no');?></td>
      <td class='c-actions text-center'>
        <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
        <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
      </td>
    </tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
