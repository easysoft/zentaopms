<table class='table-1'>
  <caption><?php echo $lang->my->profile;?></caption>
  <tr>
    <th class='rowhead'><?php echo $lang->user->account;?></th>
    <td><?php echo $app->user->account;?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->user->realname;?></th>
    <td><?php echo $app->user->realname;?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->user->nickname;?></th>
    <td><?php echo $app->user->nickname;?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->user->join;?></th>
    <td><?php echo $app->user->join;?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->user->visits;?></th>
    <td><?php echo $app->user->visits;?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->user->ip;?></th>
    <td><?php echo $app->user->ip;?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->user->last;?></th>
    <td><?php echo $app->user->last;?></td>
  </tr>
</table>
<div class='a-right'>
<?php 
echo html::a($this->createLink('my', 'editprofile'), $lang->user->editProfile);
echo html::a($this->createLink('user', 'logout'),    $lang->logout);
?>
</div>
