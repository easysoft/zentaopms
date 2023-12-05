<?php
/**
 * The batch activate view of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>

<?php
if($this->config->edition != 'open')
{
    $action = $this->loadModel('workflowaction')->getByModuleAndAction('bug', 'batchactivate');
    if($action->js)  echo "<script>{$action->js}</script>";
    if($action->css) echo "<style>{$action->css}</style>";
}
?>

<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->bug->common . $lang->colon . $lang->bug->batchActivate;?></h2>
  </div>
  <form class='main-form' method='post' target='hiddenwin'>
    <table class='table table-form table-fixed'>
      <thead>
        <tr>
          <th class='c-id'><?php echo $lang->idAB;?></th>
          <th class='c-title'><?php echo $lang->bug->title;?></th>
          <th class='c-assigned'><?php echo $lang->bug->assignedTo;?></th>
          <th class='c-build'><?php echo $lang->bug->openedBuild;?></th>
          <th><?php echo $lang->bug->legendComment;?></th>
          <?php
          $extendFields = $this->bug->getFlowExtendFields();
          foreach($extendFields as $extendField)
          {
              $required = strpos(",$extendField->rules,", ',1,') !== false ? 'required' : '';
              echo "<th class='c-extend $required'>{$extendField->name}</th>";
          }
          ?>
        </tr>
      </thead>
      <tbody class='text-left'>
        <?php foreach($bugs as $bug):?>
        <tr>
          <td class='text-center'><?php echo $bug->id . html::hidden("bugIDList[$bug->id]", $bug->id);?></td>
          <td title='<?php echo $bug->title;?>'><?php echo $bug->title . html::hidden("statusList[$bug->id]", $bug->status);?></td>
          <td style='overflow:visible'><?php echo html::select("assignedToList[$bug->id]", $users, $bug->resolvedBy, "class='form-control chosen'");?></td>
          <td style='overflow:visible'><?php echo html::select("openedBuildList[$bug->id]", $builds, $bug->openedBuild, 'size=4 multiple=multiple class="form-control chosen"');?></td>
          <td><?php echo html::input("commentList[$bug->id]", '', "class='form-control'");?></td>
          <?php
          $this->loadModel('flow');
          foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->flow->getFieldControl($extendField, $bug, $extendField->field . "[$bug->id]") . "</td>";
          ?>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr><td colspan="<?php echo count($extendFields) + 5;?>" class='text-center'><?php echo html::submitButton();?></td></tr>
      </tfoot>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
