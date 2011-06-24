<?php
/**
 * The edit view of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<form method='post' target='hiddenwin'>
  <table align='center' class='table-1 a-left'> 
    <caption><?php echo $lang->project->edit;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->project->name;?></th>
      <td><?php echo html::input('name', $project->name, "class='text-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->code;?></th>
      <td><?php echo html::input('code', $project->code, "class='text-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->begin;?></th>
      <td><?php echo html::input('begin', $project->begin, "class='text-3 date'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->end;?></th>
      <td><?php echo html::input('end', $project->end, "class='text-3 date'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->teamname;?></th>
      <td><?php echo html::input('team', $project->team, "class='text-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->status;?></th>
      <td><?php echo html::select('status', $lang->project->statusList, $project->status, 'class=text-3');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->project->PO;?></th>
      <td><?php echo html::select('PO', $users, $project->PO, 'class=text-3');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->PM;?></th>
      <td><?php echo html::select('PM', $users, $project->PM, 'class=text-3');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->QM;?></th>
      <td><?php echo html::select('QM', $users, $project->QM, 'class=text-3');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->RM;?></th>
      <td><?php echo html::select('RM', $users, $project->RM, 'class=text-3');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->manageProducts;?></th>
      <td><?php echo html::checkbox("products", $allProducts, $linkedProducts);?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->project->goal;?></th>
      <td><?php echo html::textarea('goal', htmlspecialchars($project->goal), "rows='6' class='area-1'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->desc;?></th>
      <td><?php echo html::textarea('desc', htmlspecialchars($project->desc), "rows='6' class='area-1'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->project->acl;?></th>
      <td><?php echo nl2br(html::radio('acl', $lang->project->aclList, $project->acl, "onclick='setWhite(this.value);'"));?></td>
    </tr>  
    <tr id='whitelistBox' <?php if($project->acl != 'custom') echo "class='hidden'";?>>
      <th class='rowhead'><?php echo $lang->project->whitelist;?></th>
      <td><?php echo html::checkbox('whitelist', $groups, $project->whitelist);?></td>
    </tr>  
    <tr><td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
