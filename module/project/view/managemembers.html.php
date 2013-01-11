<?php
/**
 * The link user view of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('projectID', $project->id);?>
<?php js::set('roles', $roles, 'json');?>
<form method='post'>
  <table align='center' class='table-4 a-center'> 
    <caption>
      <div class='f-left'><?php echo $lang->project->manageMembers;?></div>
      <div class='f-right'><?php echo html::select('teams2Import', $teams2Import, $team2Import, 'onchange=importTeam()');?></div>
    </caption>
    <tr>
      <th><?php echo $lang->team->account;?></th>
      <th><?php echo $lang->team->role;?></th>
      <th><?php echo $lang->team->days;?></th>
      <th><?php echo $lang->team->hours;?></th>
    </tr>
    <?php $i = 1;?>
    <?php foreach($currentMembers as $member):?>
    <?php $realname = substr($users[$member->account], 2);?>
    <?php unset($users[$member->account]);?>
    <tr>
      <td><input type='text' name='realnames[]' id='account<?php echo $i;?>' value='<?php echo $realname;?>' readonly class='text-2' /></td>
      <td><input type='text' name='roles[]'     id='role<?php echo $i;?>'    value='<?php echo $member->role;?>' class='text-2' /></td>
      <td><input type='text' name='days[] '     id='days<?php echo $i;?>'    value='<?php echo $member->days;?>' class='text-2' /></td>
      <td>
        <input type='text'   name='hours[]' id='hours<?php echo $i;?>' value='<?php echo $member->hours;?>' class='text-2' />
        <input type='hidden' name='modes[]' value='update' />
        <input type='hidden' name='accounts[]' value='<?php echo $member->acount;?>' />
      </td>
    </tr>
    <?php $i ++;?>
    <?php endforeach;?>

    <?php foreach($members2Import as $member2Import):?>
    <tr>
      <td><?php echo html::select('accounts[]', $users, $member2Import->account, "class='select-2' onchange='setRole(this.value, $i)'");?></td>
      <td><input type='text' name='roles[]' id='role<?php echo $i;?>' class='text-2' value='<?php echo $member2Import->role;?>' /></td>
      <td><input type='text' name='days[]'  id='days<?php echo $i;?>' class='text-2' value='<?php echo $project->days?>'/></td>
      <td>
        <input type='text'   name='hours[]' id='hours<?php echo $i;?>' class='text-2' value='<?php echo $member2Import->hours;?>' />
        <input type='hidden' name='modes[]' value='create' />
      </td>
    </tr>
    <?php $i ++;?>
    <?php endforeach;?>

    <?php
    $count = count($users) - 1;
    if($count > PROJECTMODEL::LINK_MEMBERS_ONE_TIME) $count = PROJECTMODEL::LINK_MEMBERS_ONE_TIME;
    ?>

    <?php for($j = 0; $j < $count; $j ++):?>
    <tr>
      <td><?php echo html::select('accounts[]', $users, '', "class='select-2' onchange='setRole(this.value, $i)'");?></td>
      <td><input type='text' name='roles[]' id='role<?php echo ($i);?>' class='text-2' /></td>
      <td><input type='text' name='days[]'  id='days<?php echo  ($i);?>' class='text-2' value='<?php echo $project->days?>'/></td>
      <td>
        <input type='text'   name='hours[]' id='hours<?php echo ($i);?>' class='text-2' value='7' />
        <input type='hidden' name='modes[]' value='create' />
      </td>
    </tr>
    <?php $i ++;?>
    <?php endfor;?>
    <tr>
      <td colspan='4'>
        <input type='submit' name='submit' value='<?php echo $lang->save;?>' class='button-s' />
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
