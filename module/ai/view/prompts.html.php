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
<?php
  /**
   * Prompt action menu printer, based on prompt status and completion state.
   *
   * @param  object $prompt  prompt object
   * @return void
   */
  $printPromptActionMenu = function($prompt) use($lang)
  {
    $html = '';
    $executable  = $this->ai->isExecutable($prompt);
    $published   = $prompt->status == 'active';

      if($this->config->edition != 'open')
      {
          /* Design button. */
          if(common::hasPriv('ai', 'promptassignrole')) $html .= html::a(helper::createLink('ai', 'promptassignrole', "prompt=$prompt->id"), '<i class="icon-design text-primary"></i>', '', "class='btn" . ($published ? ' disabled' : '') . "' title='{$lang->ai->prompts->action->design}'");

          /* Test / audit button. */
          if(common::hasPriv('ai', 'promptaudit')) $html .= html::a($executable ? $this->ai->getTestingLocation($prompt) : '#', '<i class="icon-menu-backend text-primary"></i>', '', "id='prompt-audit-button-$prompt->id' class='prompt-audit-btn btn" . ($executable && !$published ? '' : ' disabled') . "' title='{$lang->ai->prompts->action->test}'" . ($executable ? '' : " data-toggle='modal' data-target='#designConfirmModal'"));

          /* Edit button. */
          if(common::hasPriv('ai', 'promptedit')) $html .= html::a(helper::createLink('ai', 'promptedit', "prompt=$prompt->id"), '<i class="icon-edit text-primary"></i>', '', "class='btn' title='{$lang->ai->prompts->action->edit}'");

          /* Divider. */
          if(common::hasPriv('ai', 'promptpublish') || common::hasPriv('ai', 'promptunpublish')) $html .= '<div class="dividing-line"></div>';
      }
    /* Publish button. */
    if(common::hasPriv('ai', 'promptpublish')) $html .= html::a(!$published && $executable ? "javascript:togglePromptStatus($prompt->id)" : '#', '<i class="icon-publish text-primary"></i>', '', "class='btn"  . (!$published && $executable ? '' : ' disabled') . "' title='{$lang->ai->prompts->action->publish}'" . ($executable ? '' : " data-toggle='modal' data-target='#designConfirmModal'"));

    /* Unpublish button. */
    if(common::hasPriv('ai', 'promptunpublish')) $html .= html::a('#', '<i class="icon-ban text-primary"></i>', '', "class='btn" . (!$published || !$executable ? ' disabled' : '') . "' title='{$lang->ai->prompts->action->unpublish}'" . (!$published ? '' : " data-toggle='modal' data-target='#draftConfirmModal'"));

    echo $html;
  }
?>
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
        echo html::a($this->createLink('ai', 'prompts', "module=$module&status=$statusKey"), "<span class='text'>{$this->lang->ai->prompts->statuses[$statusKey]}" . ($status == $statusKey ? '<span class="label label-light label-badge" style="margin-left: 4px;">' . $pager->recTotal . '</span>' : '') . "</span>", '' ,"id='status-$statusKey' class='btn btn-link" . ($status == $statusKey ? ' btn-active-text' : '') . "'");
      }
    ?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if($this->config->edition != 'open' && common::hasPriv('ai', 'createprompt')) echo html::a($this->createLink('ai', 'createprompt'), "<i class='icon icon-plus'></i> " . $lang->ai->prompts->create, '', "class='btn btn-primary iframe'");?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="side-col" id="sidebar">
    <div class="cell">
      <ul id="modules" class="tree" data-ride="tree" data-name="tree-modules">
        <?php
        $moduleList = $this->config->edition != 'open'
          ? $this->lang->ai->prompts->modules
          : array_filter($this->lang->ai->prompts->modules, function($v, $k) use ($promptModules)
          {
            return in_array($k, $promptModules);
          }, ARRAY_FILTER_USE_BOTH);
        ?>
        <?php foreach($moduleList as $moduleKey => $moduleName): ?>
          <li <?php if($module == $moduleKey) echo 'class="active"'; ?>><a
              href="<?php echo $this->createLink('ai', 'prompts', "module=$moduleKey"); ?>"><?php echo $moduleName; ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <div class="main-col">
    <?php if(empty($prompts)):?>
      <div class="table-empty-tip">
        <p>
          <span class="text-muted"><?php echo $lang->ai->prompts->emptyList;?></span>
          <?php if($this->config->edition != 'open' && common::hasPriv('ai', 'createprompt')) echo html::a($this->createLink('ai', 'createprompt'), "<i class='icon icon-plus'></i> " . $lang->ai->prompts->create, '', "class='btn btn-info iframe'");?>
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
              <?php if(empty($status)):?>
                <th class='c-status w-80px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->ai->prompts->stage);?></th>
              <?php endif;?>
              <th class='c-createdby w-120px'><?php common::printOrderLink('createdby', $orderBy, $vars, $lang->ai->prompts->createdBy);?></th>
              <th class='c-createddate w-180px'><?php common::printOrderLink('createddate', $orderBy, $vars, $lang->ai->prompts->createdDate);?></th>
              <th class='c-targetform w-200px'><?php common::printOrderLink('targetform', $orderBy, $vars, $lang->ai->prompts->targetForm);?></th>
              <th class='text-center c-actions-5'><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($prompts as $prompt):?>
              <tr>
                <td class='c-id'><?php echo $prompt->id;?></td>
                <td class='c-name'><?php echo html::a(helper::createLink('ai', 'promptview', "id=$prompt->id"), $prompt->name, '_self', "title='$prompt->name'");?></td>
                <?php if(empty($status)):?>
                  <td class='c-status'><?php echo $lang->ai->prompts->statuses[$prompt->status];?></td>
                <?php endif;?>
                <td class='c-createdby'><?php echo zget($users, $prompt->createdBy);?></td>
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
                <td class='text-center c-actions' data-prompt-id='<?php echo $prompt->id?>'>
                  <?php $printPromptActionMenu($prompt);?>
                </td>
              </tr>
            <?php endforeach;?>
          </tbody>
        </table>
        <div class='table-footer'>
          <div class="table-statistic"><?php echo sprintf($lang->ai->prompts->summary, count($prompts));?></div>
          <?php $pager->show('right', 'pagerjs');?>
        </div>
      </div>
    <?php endif;?>
  </div>
</div>
<div class="modal fade" id="designConfirmModal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body">
        <p><?php echo $lang->ai->prompts->action->goDesignConfirm?></p>
      </div>
      <div class="modal-footer">
        <button id="goDesignButton" type="button" class="btn btn-primary"><?php echo $lang->ai->prompts->action->goDesign?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang->cancel?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="draftConfirmModal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body">
        <p><?php echo $lang->ai->prompts->action->draftConfirm?></p>
      </div>
      <div class="modal-footer">
        <button id="draftPromptButton" type="button" class="btn btn-primary"><?php echo $lang->confirm?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang->cancel?></button>
      </div>
    </div>
  </div>
</div>

<script>
  $(function()
  {
    const container = window.frameElement?.closest('.load-indicator');
    if(container && container.dataset.loading)
    {
      delete container.dataset.loading;
      container.classList.remove('loading');
      container.classList.remove('no-delay');
    }
    /* Handle clicks from audit button without auditable objects. */
    $('.prompt-audit-btn[href=""]').click(function(e)
    {
      $.zui.messager.danger('<?php echo $lang->ai->prompts->goingTestingFail;?>');
      return false;
    });
    $('.prompt-audit-btn[href!=""][href!="#"]').click(function(e)
    {
      if(!container) return;
      container.dataset.loading = '<?php echo $lang->ai->execute->auditing;?>';
      container.classList.add('loading');
      container.classList.add('no-delay');

      /* Checks for session storage to cancel loading status (see inputinject.html.php). */
      sessionStorage.removeItem('ai-prompt-data-injected');
      const loadCheckInterval = setInterval(function()
      {
        if(sessionStorage.getItem('ai-prompt-data-injected'))
        {
          if(container && container.dataset.loading)
          {
            delete container.dataset.loading;
            container.classList.remove('loading');
            container.classList.remove('no-delay');
          }
          sessionStorage.removeItem('ai-prompt-data-injected');
          clearInterval(loadCheckInterval);
        }
      }, 200);
    });
  });
</script>
<?php include '../../common/view/footer.html.php';?>
