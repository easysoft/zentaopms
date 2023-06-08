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
    <?php echo html::a($this->createLink('ai', 'createprompt'), "<i class='icon icon-plus'></i> " . $lang->ai->prompts->create, '', "class='btn btn-primary'");?>
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
          <?php echo html::a($this->createLink('ai', 'createprompt'), "<i class='icon icon-plus'></i> " . $lang->ai->prompts->create, '', "class='btn btn-info'");?>
        </p>
      </div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>