<?php
/**
 * The safe view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='featurebar'>
  <ul class='nav'>
    <li class='active'><?php echo html::a($this->inlink('safe'), $lang->admin->safe->account);?></li>
  </ul>
</div>
<div class='container mw-800px'>
  <div id="titlebar">
    <div class="heading">
      <strong><?php echo $lang->admin->safe->weakUser?></strong>
      <?php echo html::a(inlink('safe'), $lang->goback, '', "class=''")?>
    </div>
  </div>
  <table class='table table-condensed table-hover table-striped table-fixed'>
    <thead>
      <th class='w-id'><?php echo $lang->idAB;?></th>
      <th><?php echo $lang->user->realname;?></th>
      <th><?php echo $lang->user->account;?></th>
      <th><?php echo $lang->user->phone;?></th>
      <th><?php echo $lang->user->mobile;?></th>
      <th><?php echo $lang->user->birthday;?></th>
      <th class='w-50px'><?php echo $lang->actions;?></th>
    </thead>
    <tbody>
      <?php foreach($weakUsers as $user):?>
      <tr>
        <td><?php echo $user->id?></td>
        <td><?php echo $user->realname?></td>
        <td><?php echo $user->account?></td>
        <td><?php echo $user->phone?></td>
        <td><?php echo $user->mobile?></td>
        <td><?php echo $user->birthday?></td>
        <td><?php common::printIcon('user', 'edit', "userID=$user->id", '', 'list');?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
