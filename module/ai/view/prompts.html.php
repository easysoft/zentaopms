<?php
/**
 * The ai prompts view file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <div class="title" title="<?php echo $this->lang->ai->prompts->modules[$module];?>">
      <?php echo $this->lang->ai->prompts->modules[$module];?>
      <?php if($module) echo html::a($this->createLink('ai', 'prompts'), "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");?>
    </div>
  </div>
  <div class="btn-toolbar pull-left">
    <?php
      foreach($this->lang->ai->prompts->statuses as $statusKey => $statusName)
      {
        echo html::a($this->createLink('ai', 'prompts', "module=$module&status=$statusKey"), "<span class='text'>{$this->lang->ai->prompts->statuses[$statusKey]}" . ($status == $statusKey ? '<span class="label label-light label-badge" style="margin-left: 4px;">' . count($prompts) . '</span>' : '') . "</span>", '' ,"id='status-$statusKey' class='btn btn-link" . ($status == $statusKey ? ' btn-active-text' : '') . "'");
      }
    ?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php echo html::a($this->createLink('ai', 'createprompt'), "<i class='icon icon-plus'></i> " . $lang->ai->prompts->create, '', "class='btn btn-primary iframe'");?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="side-col" id="sidebar">
    <div class="cell">
      <ul id="modules" class="tree" data-ride="tree" data-name="tree-modules">
        <?php foreach($this->lang->ai->prompts->modules as $moduleKey => $moduleName):?>
          <li <?php if($module == $moduleKey) echo 'class="active"';?>><a href="<?php echo $this->createLink('ai', 'prompts', "module=$moduleKey");?>"><?php echo $moduleName;?></a></li>
        <?php endforeach;?>
      </ul>
    </div>
  </div>
  <div class="main-col">
    <?php if(empty($prompts)):?>
      <div class="table-empty-tip">
        <p>
          <span class="text-muted"><?php echo $lang->ai->prompts->emptyList;?></span>
          <?php echo html::a($this->createLink('ai', 'createprompt'), "<i class='icon icon-plus'></i> " . $lang->ai->prompts->create, '', "class='btn btn-info iframe'");?>
        </p>
      </div>
    <?php else:?>
      <div class='main-table'>
        <table class='main-table table has-sort-head table-fixed' id='promptList'>
          <thead>
            <tr>
              <?php $vars = "module=$module&status=$status&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
              <th class='c-id text-left w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->ai->prompts->id);?></th>
              <th class='c-name'><?php common::printOrderLink('name', $orderBy, $vars, $lang->ai->prompts->name);?></th>
              <th class='c-createdby w-120px'><?php common::printOrderLink('createdby', $orderBy, $vars, $lang->ai->prompts->createdBy);?></th>
              <th class='c-createddate w-180px'><?php common::printOrderLink('createddate', $orderBy, $vars, $lang->ai->prompts->createdDate);?></th>
              <th class='c-targetform'><?php common::printOrderLink('targetform', $orderBy, $vars, $lang->ai->prompts->targetForm);?></th>
              <th class='c-desc'><?php echo $lang->ai->prompts->funcDesc;?></th>
              <th class='text-center c-actions-5'><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($prompts as $prompt):?>
              <tr>
                <td class='c-id'><?php echo $prompt->id;?></td>
                <td class='c-name' title='<?php echo $prompt->name;?>'>
                  <?php echo $prompt->name;?>
                  <?php if($prompt->status == 'draft') echo "<span class='label label-light label-badge'>{$this->lang->ai->prompts->statuses[$prompt->status]}</span>";?>
                </td>
                <td class='c-createdby'><?php echo $prompt->createdBy;?></td>
                <td class='c-createddate'><?php echo $prompt->createdDate;?></td>
                <td class='c-targetform'>
                  <?php
                    if($prompt->targetForm)
                    {
                      $targetFormPath = explode('.', $prompt->targetForm);
                      if(count($targetFormPath) == 2) echo $lang->ai->targetForm[$targetFormPath[0]]['common'] . ' / ' . $lang->ai->targetForm[$targetFormPath[0]][$targetFormPath[1]];
                    }
                  ?>
                </td>
                <td class='c-description' title='<?php echo $prompt->desc;?>'><?php echo $prompt->desc;?></td>
                <td class='text-center c-actions'>
                  <?php
                    echo html::a('#', '<i class="icon-bars text-primary"></i>', '', "class='btn'");
                    echo html::a(inlink('promptassignrole', "prompt=$prompt->id"), '<i class="icon-design text-primary"></i>', '', "class='btn'");
                    echo html::a('#', '<i class="icon-edit text-primary"></i>', '', "class='btn'");
                    echo html::a('#', '<i class="icon-lock text-primary"></i>', '', "class='btn'");
                    echo html::a('#', '<i class="icon-trash text-primary"></i>', '', "class='btn'");
                  ?>
                </td>
              </tr>
            <?php endforeach;?>
          </tbody>
        </table>
        <div class='table-footer'>
          <?php $pager->show('right', 'pagerjs');?>
        </div>
      </div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
