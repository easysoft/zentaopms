<?php
/**
 * The create view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: create.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container mw-500px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix' title='GROUP'><?php echo html::icon($lang->icons['group']);?></span>
      <strong><small><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->group->create;?></strong>
    </div>
  </div>
  <form class='form-condensed mw-500px pdb-20' method='post' target='hiddenwin' id='dataform'>
    <table align='center' class='table table-form'> 
      <tr>
        <th class='w-80px'><?php echo $lang->group->name;?></th>
        <td><?php echo html::input('name', '', "class=form-control");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->group->desc;?></th>
        <td><?php echo html::textarea('desc', '', "rows=5 class=form-control");?></td>
      </tr>  
      <tr><th></th><td><?php echo html::submitButton();?></td></tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
