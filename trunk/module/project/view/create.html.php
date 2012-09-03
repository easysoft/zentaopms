<?php
/**
 * The create view of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
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
<?php js::import($jsRoot . 'misc/date.js');?>
<script>var holders=<?php echo json_encode($lang->project->placeholder);?></script>
<form method='post' target='hiddenwin' id='dataform'>
  <table align='center' class='table-1 a-left'> 
    <caption>
      <div class='f-left'><?php echo $lang->project->create;?></div>
      <div class='f-right'><?php echo html::checkbox('', $lang->project->copy . '?', '', 'onclick=switchCopyProject(this);');?></div>
    </caption>
    <?php if($projects):?>
    <tr id='copyProjectBox' <?php if($copyProjectID == 0) echo "class='hidden'";?>>
      <th class='rowhead'><?php echo $lang->project->copy;?></th>
      <td><?php echo html::select('', $projects, $copyProjectID, "class='select-3' onchange=setCopyProject(this.value)");?></td>
    </tr>  
    <?php endif;?>
    <tr>
      <th class='rowhead'><?php echo $lang->project->name;?></th>
      <td><?php echo html::input('name', $name, "class='text-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->code;?></th>
      <td><?php echo html::input('code', $code, "class='text-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->begin;?></th>
      <td><?php echo html::input('begin',date('Y-m-d'), "class='text-3 date' onchange='computeWorkDays()'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->end;?></th>
      <td>
         <?php echo html::input('end', '', "class='text-3 date' onchange='computeWorkDays()'");?>
         <span><?php echo html::radio('delta', $lang->project->endList , '', "onclick='computeEndDate(this.value)'");?></span>
      </td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->project->days;?></th>
      <td><?php echo html::input('days', '', "class='text-3'") . $lang->project->day;?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->teamname;?></th>
      <td><?php echo html::input('team', $team, "class='text-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->manageProducts;?></th>
      <td id='productsBox'><?php echo html::checkbox("products", $allProducts, $products);?></td>
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
      <td><?php echo nl2br(html::radio('acl', $lang->project->aclList, $acl, "onclick='setWhite(this.value);'"));?></td>
    </tr>  
    <tr id='whitelistBox' <?php if($acl != 'custom') echo "class='hidden'";?>>
      <th class='rowhead'><?php echo $lang->project->whitelist;?></th>
      <td><?php echo html::checkbox('whitelist', $groups, $whitelist);?></td>
    </tr>  
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
