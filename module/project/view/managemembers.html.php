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
<script>
projectID = <?php echo $project->id;?>;
function importTeam()
{
    location.href = createLink('project', 'manageMembers', 'project=' + projectID + '&teamImport=' + $('#teams2Import').val());
}
</script>

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
    <?php foreach($currentMembers as $key => $member):?>
    <?php $realname = substr($users[$member->account], 2);?>
    <?php unset($users[$member->account]);?>
    <tr>
      <td><input type='text' name='realnames[]' id='account<?php echo $key;?>' value='<?php echo $realname;?>' readonly class='text-2' /></td>
      <td class='hidden'><input type='text' name='accounts[]' id='account<?php echo $key;?>' value='<?php echo $member->account;?>' readonly class='text-2' /></td>
      <td><input type='text' name='roles[]'    id='role<?php echo $key;?>'    value='<?php echo $member->role;?>' class='text-2' /></td>
      <td><input type='text'   name='days[] '  id='days<?php echo $key;?>'    value='<?php echo $member->days;?>' class='text-2' /></td>
      <td>
        <input type='text'   name='hours[]' id='hours<?php echo $key;?>' value='<?php echo $member->hours;?>' class='text-2' />
        <input type='hidden' name='modes[]' value='update' />
      </td>
    </tr>
    <?php endforeach;?>

    <?php if(!$currentMembers) $key = 0;?>
    <?php foreach($members2Import as $member2Import):?>
    <?php $key ++;?>
    <tr>
      <td><?php echo html::select('accounts[]', $users, $member2Import->account, 'class=select-2');?></td>
      <td><input type='text' name='roles[]' id='role<?php echo $key;?>' class='text-2' value='<?php echo $member2Import->role;?>' /></td>
      <td><input type='text'   name='days[]'  id='days<?php echo  $key;?>' class='text-2' value='<?php echo $project->days?>'/></td>
      <td>
        <input type='text'   name='hours[]' id='hours<?php echo $key;?>' class='text-2' value='<?php echo $member2Import->hours;?>' />
        <input type='hidden' name='modes[]' value='create' />
      </td>
    </tr>
    <?php endforeach;?>

    <?php
    $count = count($users) - 1;
    if($count > PROJECTMODEL::LINK_MEMBERS_ONE_TIME) $count = PROJECTMODEL::LINK_MEMBERS_ONE_TIME;
    ?>

    <?php for($i = 0; $i < $count; $i ++):?>
    <tr>
      <td><?php echo html::select('accounts[]', $users, '', 'class=select-2');?></td>
      <td><input type='text' name='roles[]' id='role<?php echo ($key + $i);?>' class='text-2' /></td>
      <td><input type='text'   name='days[]'  id='days<?php echo  ($key + $i);?>' class='text-2' value='<?php echo $project->days?>'/></td>
      <td>
        <input type='text'   name='hours[]' id='hours<?php echo ($key + $i);?>' class='text-2' value='7' />
        <input type='hidden' name='modes[]' value='create' />
      </td>
    </tr>
    <?php endfor;?>
    <tr>
      <td colspan='4'>
        <input type='submit' name='submit' value='<?php echo $lang->save;?>' class='button-s' />
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
