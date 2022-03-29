<?php include $this->app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('projectID', $project->id);?>
<?php js::set('roles', $roles);?>
<?php js::set('deptID', $dept);?>
<?php js::set('pickerUsers', $userInfoList);?>
<?php js::set('copyProjectID', $copyProjectID);?>
<?php js::set('oldAccountList', array_keys($currentMembers));?>
<?php js::set('unlinkExecutionMembers', $lang->project->unlinkExecutionMembers);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'>
      <?php echo html::a($this->createLink('project', 'managemembers', "projectID={$project->id}"), "<span class='text'> {$lang->project->manageMembers}</span>");?>
    </span>
    <div class='input-group space w-200px'>
      <span class='input-group-addon'><?php echo $lang->execution->selectDept?></span>
      <?php echo html::select('dept', $depts, $dept, "class='form-control chosen' onchange='setDeptUsers(this)' data-placeholder='{$lang->execution->selectDeptTitle}'");?>
      <?php if(count($teams2Import) > 1):?>
      <span class='input-group-addon'><?php echo $lang->execution->copyTeam;?></span>
      <?php echo html::select('project', $teams2Import, $copyProjectID, "class='form-control chosen' onchange='choseTeam2Copy(this)' data-placeholder='{$lang->project->copyTeamTitle}'");?>
      <?php endif;?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <form class='main-form form-ajax' method='post' id='teamForm'>
    <table class='table table-form'>
      <thead>
        <tr class='text-center'>
          <th><?php echo $lang->team->account;?></th>
          <th><?php echo $lang->team->role;?></th>
          <th class='c-days hidden'><?php echo $lang->team->days;?></th>
          <th class='c-hours hidden'><?php echo $lang->team->hours;?></th>
          <th class='c-limited hidden'><?php echo $lang->team->limited;?></th>
          <th class="c-actions"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 0;?>
        <?php foreach($currentMembers as $member):?>
        <?php if(!isset($users[$member->account])) continue;?>
        <?php unset($users[$member->account]);?>
        <tr>
          <td><?php echo html::input("realnames[$i]", $member->realname, "class='form-control' readonly");?></td>
          <td><?php echo html::input("roles[$i]", $member->role, "class='form-control'");?></td>
          <td class='hidden'><?php echo html::input("days[$i]", $member->days, "class='form-control'");?></td>
          <td class='hidden'><?php echo html::input("hours[$i]", $member->hours, "class='form-control'");?></td>
          <td class='hidden'><?php echo html::radio("limited[$i]", $lang->team->limitedList, $member->limited);?></td>
          <td class='c-actions text-center'>
            <?php echo html::hidden("accounts[$i]", $member->account);?>
            <?php echo html::a('javascript:;', "<i class='icon-plus'></i>", '', "onclick='addItem(this)' class='btn btn-link'");?>
            <?php echo html::a('javascript:;', "<i class='icon icon-close'></i>", '', "onclick='deleteItem(this)' class='btn btn-link'");?>
          </td>
        </tr>
        <?php $i ++;?>
        <?php endforeach;?>

        <?php foreach($deptUsers as $deptAccount => $userName):?>
        <?php if(!isset($users[$deptAccount])) continue;?>
        <tr class='addedItem'>
          <td><?php echo html::select("accounts[$i]", $users, $deptAccount, "class='form-control chosen' onchange='setRole(this.value, $i)'");?></td>
          <td><?php echo html::input("roles[$i]", $roles[$deptAccount], "class='form-control'");?></td>
          <td class='hidden'><?php echo html::input("days[$i]", $project->days, "class='form-control'");?></td>
          <td class='hidden'><?php echo html::input("hours[$i]", $config->execution->defaultWorkhours, "class='form-control'");?></td>
          <td class='hidden'><?php echo html::radio("limited[$i]", $lang->team->limitedList, 'no');?></td>
          <td class='c-actions text-center'>
            <?php echo html::a('javascript:;', "<i class='icon-plus'></i>", '', "onclick='addItem(this)' class='btn btn-link'");?>
            <?php echo html::a('javascript:;', "<i class='icon icon-close'></i>", '', "onclick='deleteItem(this)' class='btn btn-link'");?>
          </td>
        </tr>
        <?php unset($users[$deptAccount]);?>
        <?php $i ++;?>
        <?php endforeach;?>

        <?php foreach($members2Import as $member2Import):?>
        <?php if(!isset($users[$member2Import->account])) continue;?>
        <tr class='addedItem'>
          <td><?php echo html::select("accounts[$i]", $users, $member2Import->account, "class='form-control user-picker' onchange='setRole(this.value, $i)'");?></td>
          <td><?php echo html::input("roles[$i]", $member2Import->role, "class='form-control'");?></td>
          <td class='hidden'><?php echo html::input("days[$i]", $project->days, "class='form-control'");?></td>
          <td class='hidden'><?php echo html::input("hours[$i]", $member2Import->hours, "class='form-control'");?></td>
          <td class='hidden'><?php echo html::radio("limited[$i]", $lang->team->limitedList, 'no');?></td>
          <td class='c-actions text-center'>
            <?php echo html::a('javascript:;', "<i class='icon-plus'></i>", '', "onclick='addItem(this)' class='btn btn-link'");?>
            <?php echo html::a('javascript:;', "<i class='icon icon-close'></i>", '', "onclick='deleteItem(this)' class='btn btn-link'");?>
          </td>
        </tr>
        <?php unset($users[$member2Import->account]);?>
        <?php $i ++;?>
        <?php endforeach;?>

        <?php for($j = 0; $j < 5; $j ++):?>
        <tr class='addedItem'>
          <td><?php echo html::select("accounts[$i]", $users, '', "class='form-control user-picker' onchange='setRole(this.value, $i)'");?></td>
          <td><?php echo html::input("roles[$i]", '', "class='form-control'");?></td>
          <td class='hidden'><?php echo html::input("days[$i]", $project->days, "class='form-control'");?></td>
          <td class='hidden'><?php echo html::input("hours[$i]", $config->execution->defaultWorkhours, "class='form-control'");?></td>
          <td class='hidden'><?php echo html::radio("limited[$i]", $lang->team->limitedList, 'no');?></td>
          <td class='c-actions text-center'>
            <?php echo html::a('javascript:;', "<i class='icon-plus'></i>", '', "onclick='addItem(this)' class='btn btn-link'");?>
            <?php echo html::a('javascript:;', "<i class='icon icon-close'></i>", '', "onclick='deleteItem(this)' class='btn btn-link'");?>
          </td>
        </tr>
        <?php $i ++;?>
        <?php endfor;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php
              echo html::hidden('removeExecution', 'no');
              if(commonModel::isTutorialMode())
              {
                  echo html::submitButton($lang->save, '', 'btn btn-wide btn-primary');
              }
              else
              {
                  echo html::submitButton('', '', 'hidden btn btn-wide btn-primary');
                  echo html::commonButton($lang->save, 'onclick="saveMembers()" id="saveBtn"', 'btn btn-wide btn-primary');
              }
              echo html::backButton();
            ?>
          </td>
        </tr>
      </tfoot>
    </table>
    <?php js::set('itemIndex', $i);?>
  </form>
</div>
<div>
  <?php $i = '%i%';?>
  <table class='hidden'>
    <tr id='addItem' class='hidden'>
      <td><?php echo html::select("accounts[$i]", $users, '', "class='form-control' onchange='setRole(this.value, $i)'");?></td>
      <td><?php echo html::input("roles[$i]", '', "class='form-control'");?></td>
      <td class='hidden'><?php echo html::input("days[$i]", $project->days, "class='form-control'");?></td>
      <td class='hidden'><?php echo html::input("hours[$i]", $config->execution->defaultWorkhours, "class='form-control'");?></td>
      <td class='hidden'><?php echo html::radio("limited[$i]", $lang->team->limitedList, 'no');?></td>
      <td class='c-actions text-center'>
        <?php echo html::a('javascript:;', "<i class='icon-plus'></i>", '', "onclick='addItem(this)' class='btn btn-link'");?>
        <?php echo html::a('javascript:;', "<i class='icon icon-close'></i>", '', "onclick='deleteItem(this)' class='btn btn-link'");?>
      </td>
    </tr>
  </table>
</div>
<?php include $this->app->getModuleRoot() . 'common/view/footer.html.php';?>
