<?php
/**
 * The create view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: create.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'> 
  <div class='main-header'>
    <h2><?php echo $lang->group->create;?></h2>
  </div>
  <form method='post' target='hiddenwin' id='dataform'>
    <table align='center' class='table table-form'> 
      <tr>
        <th><?php echo $lang->group->name;?></th>
        <td><?php echo html::input('name', '', "class='form-control' autocomplete='off'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->group->desc;?></th>
        <td><?php echo html::textarea('desc', '', "rows=5 class=form-control");?></td>
      </tr>  
      <tr>
        <td colspan='2' class='text-center'><?php echo html::submitButton('', '', 'btn btn-primary btn-wide');?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
