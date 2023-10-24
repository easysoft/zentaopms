<?php
/**
 * The ai prompt details view file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php $this->app->tab = 'admin'; // Force tab to be `admin` so that feature menu will be printed correctly. ?>
<?php include '../../common/view/header.html.php';?>

<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a(helper::createLink('ai', 'prompts'), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $prompt->id;?></span>
      <span class="text" title='<?php echo $prompt->name;?>'><?php echo $prompt->name;?></span>
      <?php if($prompt->deleted) echo "<span class='label label-danger'>{$lang->ai->prompts->deleted}</span>";?>
    </div>
  </div>
  <?php if($this->config->edition != 'open'): ?>
    <div class="btn-toolbar pull-right">
      <?php if(common::hasPriv('ai', 'createprompt')) echo html::a(helper::createLink('ai', 'createprompt'), "<i class='icon icon-plus'></i> {$lang->ai->prompts->create}", '', "class='btn btn-primary iframe'"); ?>
    </div>
  <?php endif; ?>
</div>

<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell prompt-details">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->ai->prompts->role;?></div>
        <div class="detail-content article-content"><?php echo $prompt->role;?></div>
        <div class="detail-title"><?php echo $lang->ai->prompts->characterization;?></div>
        <div class="detail-content article-content"><?php echo $prompt->characterization;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->ai->prompts->object;?></div>
        <div class="detail-content article-content"><?php echo $prompt->module ? $this->lang->ai->dataSource[$prompt->module]['common'] : ''; ?></div>
        <div class="detail-title"><?php echo $lang->ai->prompts->field;?></div>
        <div class="detail-content article-content"><?php echo $dataPreview;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->ai->prompts->setPurpose;?></div>
        <div class="detail-content article-content"><?php echo $prompt->purpose;?></div>
        <div class="detail-title"><?php echo $lang->ai->prompts->elaboration;?></div>
        <div class="detail-content article-content"><?php echo $prompt->elaboration;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->ai->prompts->selectTargetForm;?></div>
        <div class="detail-content article-content">
          <?php
            if(empty($prompt->targetForm)) echo '';
            else
            {
              $targetForm = explode('.', $prompt->targetForm);
              echo $this->lang->ai->targetForm[$targetForm[0]][$targetForm[1]];
            }
          ?></div>
      </div>
    </div>
    <div class="cell"><?php include '../../common/view/action.html.php';?></div>
    <?php if($this->config->edition != 'open'): ?>
      <div class='main-actions'>
        <div class="btn-toolbar">
          <?php echo html::a(helper::createLink('ai', 'prompts'), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn'"); ?>
          <?php if(!$prompt->deleted): ?>
            <?php if(($prompt->status == 'draft' && (common::hasPriv('ai', 'promptassignrole') || common::hasPriv('ai', 'promptaudit') || common::hasPriv('ai', 'publish'))) || ($prompt->status != 'draft' && common::hasPriv('ai', 'unpublish'))): ?>
              <div class='divider'></div><?php endif; ?>
            <?php if(common::hasPriv('ai', 'promptassignrole') && $prompt->status == 'draft') echo html::a(helper::createLink('ai', 'promptassignrole', "prompt=$prompt->id"), '<i class="icon icon-design icon-sm"></i> ' . $lang->ai->prompts->action->design, '', "class='btn'"); ?>
            <?php if(common::hasPriv('ai', 'promptaudit') && $prompt->status == 'draft' && $this->ai->isExecutable($prompt)) echo html::a($this->ai->getTestingLocation($prompt), '<i class="icon icon-menu-backend icon-sm"></i> ' . $lang->ai->prompts->action->test, '', "class='btn prompt-audit-btn'"); ?>
            <?php if(common::hasPriv('ai', 'promptpublish') && $prompt->status == 'draft' && $this->ai->isExecutable($prompt)) echo html::a(helper::createLink('ai', 'promptpublish', "id={$prompt->id}"), '<i class="icon icon-publish icon-sm"></i> ' . $lang->ai->prompts->action->publish, '', "class='btn'"); ?>
            <?php if(common::hasPriv('ai', 'promptunpublish') && $prompt->status == 'active') echo html::a(helper::createLink('ai', 'promptunpublish', "id={$prompt->id}"), '<i class="icon icon-ban icon-sm"></i> ' . $lang->ai->prompts->action->unpublish, '', "class='btn' id='unpublish-btn'"); ?>
            <?php if(common::hasPriv('ai', 'promptedit') || common::hasPriv('ai', 'promptdelete')): ?>
              <div class='divider'></div><?php endif; ?>
            <?php if(common::hasPriv('ai', 'promptedit')) echo html::a(helper::createLink('ai', 'promptedit', "prompt=$prompt->id"), '<i class="icon icon-edit icon-sm"></i>', '', "class='btn' title='{$lang->ai->prompts->action->edit}'"); ?>
            <?php if(common::hasPriv('ai', 'promptdelete')) echo html::a(helper::createLink('ai', 'promptdelete', "prompt=$prompt->id"), '<i class="icon icon-trash icon-sm"></i>', '', "class='btn deleter' title='{$lang->ai->prompts->action->delete}'"); ?>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#promptBasicInfo' data-toggle='tab'><?php echo $lang->ai->prompts->basicInfo; ?></a></li>
          <li><a href='#promptEditInfo' data-toggle='tab'><?php echo $lang->ai->prompts->editInfo; ?></a></li>
        </ul>
      </div>
      <div class='tab-content'>
        <div class='tab-pane active' id='promptBasicInfo'>
          <table class="table table-data">
            <tbody>
            <tr>
              <th class="w-90px"><?php echo $lang->prompt->module; ?></th>
              <td><?php echo $prompt->module ? $this->lang->ai->dataSource[$prompt->module]['common'] : ''; ?></td>
            </tr>
            <tr>
              <th class="w-90px"><?php echo $lang->prompt->desc; ?></th>
              <td><?php echo $prompt->desc; ?></td>
            </tr>
            <tr>
              <th class="w-90px"><?php echo $lang->prompt->status; ?></th>
              <td><?php echo $lang->ai->prompts->statuses[$prompt->status]; ?></td>
            </tr>
            <tr>
              <th class="w-90px"><?php echo $lang->prompt->model; ?></th>
              <td><?php echo $lang->ai->prompts->modelNeutral; ?></td>
            </tr>
            </tbody>
          </table>
        </div>
        <div class='tab-pane' id='promptEditInfo'>
          <table class="table table-data">
            <tbody>
            <tr>
              <th class="w-90px"><?php echo $lang->prompt->createdBy; ?></th>
              <td><?php echo zget($users, $prompt->createdBy) . $lang->at . $prompt->createdDate;?></td>
            </tr>
            <?php
              /* Search for last publish / unpublish action. */
              $lastPublishAction = null;
              foreach(array_reverse($actions) as $action)
              {
                if(in_array($action->action, array('published', 'unpublished'))) $lastPublishAction = $action;
                break;
              }
            ?>
            <?php if($prompt->status == 'active'):?>
              <tr>
                <th class="w-90px"><?php echo $lang->ai->prompts->publishedBy; ?></th>
                <td><?php echo zget($users, empty($lastPublishAction) ? $prompt->createdBy : $lastPublishAction->actor);?></td>
              </tr>
            <?php else:?>
              <tr>
                <th class="w-90px"><?php echo empty($lastPublishAction) ? $lang->ai->prompts->publishedBy : $lang->ai->prompts->draftedBy;?></th>
                <td><?php if(!empty($lastPublishAction)) echo zget($users, $lastPublishAction->actor);?></td>
              </tr>
            <?php endif;?>
            <tr>
              <th class="w-90px"><?php echo $lang->prompt->editedBy; ?></th>
              <td><?php echo $prompt->editedBy ? zget($users, $prompt->editedBy) . $lang->at . $prompt->editedDate : '';?></td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
  </div>
</div>

<div id="mainActions" class='main-actions'>
  <?php common::printPreAndNext($preAndNext, helper::createLink('ai', 'promptview', 'id=%d'));?>
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

    /* TODO: use new modal for these. */
    $('.deleter').click(function()
    {
      if(confirm('<?php echo $lang->ai->prompts->action->deleteConfirm;?>'))
      {
        $.getJSON($(this).data('href'), function(resp)
        {
          if(resp.result != 'success') $.zui.messager.danger(resp.message);
          return location.reload();
        });
      }
      return false;
    });

    $('#unpublish-btn').click(function()
    {
      if(confirm('<?php echo $lang->ai->prompts->action->draftConfirm;?>'))
      {
        $.getJSON($(this).attr('href'), function(resp)
        {
          if(resp.result != 'success') $.zui.messager.danger(resp.message);
          return location.reload();
        });
      }
      return false;
    });

    $('.prompt-audit-btn').click(function()
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
