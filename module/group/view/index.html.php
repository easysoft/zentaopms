<?php
/**
 * The index view file of group module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class="yui-d0 yui-t2">                 
  <div class="yui-b a-center">
    <table class='table-1'>
      <caption><?php echo $lang->group->global;?></caption>
      <tr>
        <td>
          <?php echo html::a($this->createLink('company', 'browse'), $lang->company->browse);?><br />
          <?php echo html::a($this->createLink('company', 'create'), $lang->company->create);?><br />
          <?php echo html::a($this->createLink('company', 'edit'), $lang->company->edit);?><br />
        </td>
      </tr>
    </table>
    <table class='table-1'>
      <caption><?php echo $lang->group->user;?></caption>
      <tr>
        <td>
          <?php echo html::a($this->createLink('user', 'browse'), $lang->user->browse);?><br />
          <?php echo html::a($this->createLink('user', 'create'), $lang->user->create);?><br />
          <?php echo html::a($this->createLink('group', 'browse'), $lang->group->browse);?><br />
          <?php echo html::a($this->createLink('group', 'create'), $lang->group->create);?><br />
        </td>
      </tr>
    </table>
  </div>
  <div class="yui-main">
    <div class="yui-b">
      <table align='center' class='table-1'>
        <caption><?php echo $lang->group->index;?></caption>
      </table>
    </div>
  </div>
</div>  
<?php include '../../common/view/footer.html.php';?>
