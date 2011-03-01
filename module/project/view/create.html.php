<?php
/**
 * The create view of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(isset($tips)):?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/colorbox.html.php';?>
<body style='background:white'>
<script language='Javascript'>
var tips      = <?php echo json_encode($tips);?>;
var projectID = <?php echo $projectID;?>;
defaultURL    = createLink('project', 'task', 'projectID=' + projectID);
$(document).ready(function() 
{
    $.fn.colorbox({html:tips, open:true, width:480, height:280});
    setTimeout( function() {location.href=defaultURL}, 5000);
});
</script>
</body>
</html>
<?php exit;?>
<?php endif;?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<form method='post' target='hiddenwin'>
  <table align='center' class='table-1 a-left'> 
    <caption><?php echo $lang->project->create;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->project->name;?></th>
      <td><?php echo html::input('name', '', "class='text-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->code;?></th>
      <td><?php echo html::input('code', '', "class='text-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->begin;?></th>
      <td><?php echo html::input('begin', '', "class='text-3 date'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->end;?></th>
      <td><?php echo html::input('end', '', "class='text-3 date'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->teamname;?></th>
      <td><?php echo html::input('team', '', "class='text-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->manageProducts;?></th>
      <td><?php echo html::checkbox("products", $allProducts, '');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->project->goal;?></th>
      <td><?php echo html::textarea('goal', '', "rows='6' class='area-1'");?></td>
    </tr>  

    <tr>
      <th class='rowhead'><?php echo $lang->project->desc;?></th>
      <td><?php echo html::textarea('desc', '', "rows='6' class='area-1'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->acl;?></th>
      <td><?php echo nl2br(html::radio('acl', $lang->project->aclList, 'open', "onclick='setWhite(this.value);'"));?></td>
    </tr>  
    <tr id='whitelistBox' class='hidden'>
      <th class='rowhead'><?php echo $lang->project->whitelist;?></th>
      <td><?php echo html::checkbox('whitelist', $groups);?></td>
    </tr>  
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
