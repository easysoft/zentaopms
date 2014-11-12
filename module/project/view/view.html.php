<?php
/**
 * The view method view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: view.html.php 4594 2013-03-13 06:16:02Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['project']);?> <strong><?php echo $project->id;?></strong></span>
    <strong><?php echo $project->name;?></strong>
    <?php if($project->deleted):?>
    <span class='label label-danger'><?php echo $lang->project->deleted;?></span>
    <?php endif; ?>
  </div>
  <div class='actions'>
    <?php
    $params = "project=$project->id";
    $browseLink = $this->session->projectList ? $this->session->projectList : inlink('browse', "projectID=$project->id");
    if(!$project->deleted)
    {
        ob_start();
        echo "<div class='btn-group'>";
        common::printIcon('project', 'start',    "projectID=$project->id", $project, 'button', '', '', 'iframe', true);
        common::printIcon('project', 'activate', "projectID=$project->id", $project, 'button', '', '', 'iframe', true);
        common::printIcon('project', 'putoff',   "projectID=$project->id", $project, 'button', '', '', 'iframe', true);
        common::printIcon('project', 'suspend',  "projectID=$project->id", $project, 'button', '', '', 'iframe', true);
        common::printIcon('project', 'close',    "projectID=$project->id", $project, 'button', '', '', 'iframe', true);
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printIcon('project', 'edit', $params);
        common::printIcon('project', 'delete', $params, '', 'button', '', 'hiddenwin');
        echo '</div>';
        common::printRPN($browseLink);

        $actionLinks = ob_get_contents();
        ob_end_clean();
        echo $actionLinks;
    }
    else
    {
        common::printRPN($browseLink);
    }
    ?>
  </div>
</div>
<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <fieldset>
        <legend><?php echo $lang->project->desc;?></legend>
        <div class='content'><?php echo $project->desc;?></div>
      </fieldset>
      <?php include '../../common/view/action.html.php';?>
      <div class='actions'> <?php if(!$project->deleted) echo $actionLinks;?></div>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->project->basicInfo?></legend>
        <table class='table table-data table-condensed table-borderless'>
          <tr>
            <th class='w-80px text-right strong'><?php echo $lang->project->name;?></th>
            <td><?php echo $project->name;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->project->code;?></th>
            <td><?php echo $project->code;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->project->beginAndEnd;?></th>
            <td><?php echo $project->begin . ' ~ ' . $project->end;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->project->days;?></th>
            <td><?php echo $project->days;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->project->type;?></th>
            <td><?php echo $lang->project->typeList[$project->type];?></td>
          </tr>
          <tr> 
            <th><?php echo $lang->project->status;?></th>
            <td class='<?php echo $project->status;?>'><?php $lang->show($lang->project->statusList, $project->status);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->project->PM;?></th>
            <td><?php echo zget($users, $project->PM, $project->PM);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->project->PO;?></th>
            <td><?php echo zget($users, $project->PO, $project->PO);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->project->QD;?></th>
            <td><?php echo zget($users, $project->QD, $project->QD);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->project->RD;?></th>
            <td><?php echo zget($users, $project->RD, $project->RD);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->project->products;?></th>
            <td>
              <?php foreach($products as $productID => $productName) echo html::a($this->createLink('product', 'browse', "productID=$productID"), $productName) . '<br />';?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->project->acl;?></th>
            <td><?php echo $lang->project->aclList[$project->acl];?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->project->whitelist;?></th>
            <td>
              <?php
              $whitelist = explode(',', $project->whitelist);
              foreach($whitelist as $groupID) if(isset($groups[$groupID])) echo $groups[$groupID] . '&nbsp;';
              ?>
            </td>
          </tr>  
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->project->otherInfo?></legend>
        <table class='table table-data table-condensed table-borderless'>
          <tr>
            <th class='w-80px'><?php echo $lang->project->lblStats;?></th>
            <td><?php printf($lang->project->stats, $project->totalHours, $project->totalEstimate, $project->totalConsumed, $project->totalLeft, 10)?></td>
         </tr>
        </table>
      </fieldset>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
