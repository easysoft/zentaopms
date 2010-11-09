<?php
/**
 * The profile view file of user module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='yui-d0'>
  <table align='center' class='table-4'>
    <caption><?php echo $lang->user->profile;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->user->account;?></th>
      <td><?php echo $user->account;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->user->realname;?></th>
      <td><?php echo $user->realname;?></td>
    </tr>
    <!--
    <tr>
      <?php // echo $lang->user->nickname;?>
      <?php // echo $user->nickname;?>
    </tr>
    -->
    <tr>
      <th class='rowhead'><?php echo $lang->user->email;?></th>
      <td><?php echo $user->email;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->user->join;?></th>
      <td><?php echo $user->join;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->user->visits;?></th>
      <td><?php echo $user->visits;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->user->ip;?></th>
      <td><?php echo $user->ip;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->user->last;?></th>
      <td><?php echo $user->last;?></td>
    </tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
