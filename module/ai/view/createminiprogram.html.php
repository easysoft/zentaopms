<?php

/**
 * The create ai mini program file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<div id="mainContent" class="main-content" style="position: fixed; top: 66px; right: 16px; bottom: 16px; left: 16px;">
  <div style="width: 660px; margin: 0 auto;">
    <div style="display: flex; align-items: center; justify-content: flex-start; margin-bottom: 20px;">
      <strong style="font-size: 16px;"><?php echo $lang->ai->miniPrograms->configuration; ?></strong>
      <i title="<?php echo $lang->help; ?>" class="icon icon-help text-warning" style="padding-left: 8px; padding-right: 2px;"></i>
      <span class="text-muted"><?php echo $lang->ai->miniPrograms->downloadTip; ?></span>
      <a class="text-primary" href="https://www.zentao.net/page/download-client.html" target="_blank">&gt;&gt;<?php echo $lang->ai->miniPrograms->download; ?></a>
    </div>
    <form class="main-form form-ajax" method="post">
      <table class="table table-form">
        <tr>
          <th><?php echo $lang->ai->miniPrograms->category; ?></th>
          <td>
            <?php echo html::select('category', $lang->ai->miniPrograms->categoryList, '', "class='form-control chosen' required"); ?>
          </td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->prompt->model; ?></th>
          <td>
            <?php echo html::select('model', $lang->ai->miniPrograms->modelList, '', "class='form-control chosen' required"); ?>
          </td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->prompt->name; ?></th>
          <td colspan="2">
            <?php echo html::input('name', $name, "class='form-control' required placeholder='" . $lang->ai->miniPrograms->placeholder->name . "'"); ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->ai->miniPrograms->desc; ?></th>
          <td colspan="2">
            <?php echo html::input('desc', $introduction, "class='form-control' placeholder='" . $lang->ai->miniPrograms->placeholder->desc . "'"); ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->ai->miniPrograms->icon; ?></th>
          <td>
            <button id="ai-edit-icon" style="width: 46px; height: 46px; border-radius: 50%; border: 1px solid <?php echo $config->ai->miniPrograms->themeList[$iconTheme][1]; ?>; background-color: <?php echo $config->ai->miniPrograms->themeList[$iconTheme][0]; ?>;" type="button" class="btn btn-icon" data-toggle="modal" data-target="#edit-icon-modal">
              <?php echo $config->ai->miniPrograms->iconList[$iconName]; ?>
              <div id="edit-icon">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5.78327 6.01523C5.78327 5.80353 5.95489 5.6319 6.1666 5.6319H11.3142C11.5259 5.6319 11.6976 5.46028 11.6976 5.24857C11.6976 5.03686 11.5259 4.86523 11.3142 4.86523H6.1666C5.53147 4.86523 5.0166 5.38011 5.0166 6.01523V13.6819C5.0166 14.317 5.53147 14.8319 6.1666 14.8319H13.8333C14.4684 14.8319 14.9833 14.317 14.9833 13.6819V8.53428C14.9833 8.32257 14.8116 8.15095 14.5999 8.15095C14.3882 8.15095 14.2166 8.32257 14.2166 8.53428V13.6819C14.2166 13.8936 14.045 14.0652 13.8333 14.0652H6.1666C5.95489 14.0652 5.78327 13.8936 5.78327 13.6819V6.01523Z" fill="#2E7FFF" />
                  <path d="M14.9 5.55565C15.0515 5.40777 15.0545 5.16508 14.9066 5.01357C14.7587 4.86207 14.516 4.85912 14.3645 5.007L8.93315 10.3082C8.78165 10.456 8.7787 10.6987 8.92658 10.8502C9.07445 11.0018 9.31714 11.0047 9.46865 10.8568L14.9 5.55565Z" fill="#2E7FFF" />
                </svg>
              </div>
            </button>
          </td>
          <td class="hidden">
            <?php echo html::input('iconName', $iconName, "class='form-control'") ?>
            <?php echo html::input('iconTheme', $iconTheme, "class='form-control'") ?>
          </td>
        </tr>
        <div style="position: fixed; left: 0; right: 0; bottom: 32px; display: flex; justify-content: center; gap: 24px;">
          <?php echo html::a($this->createLink('ai', 'miniPrograms'), $lang->goback, '', "class='btn btn-back btn-wide'"); ?>
          <button class="btn btn-wide btn-secondary"><?php echo $lang->save; ?></button>
          <button class="btn btn-wide btn-primary" type="submit"><?php echo $lang->ai->nextStep; ?></button>
        </div>
      </table>
    </form>
  </div>
</div>
<div class="modal fade" id="edit-icon-modal">
  <div class="modal-dialog" style="width: 600px;">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom: none;">
        <div style="display: inline-flex; gap: 8px; align-items: center;">
          <strong style="font-size: 20px;"><?php echo $lang->ai->miniPrograms->iconModification; ?></strong>
          <span class="text-muted">Emoji icons by Twemoji with CC-BY4.0</span>
        </div>
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body" style="display: flex; gap: 42px;">
        <div class="icon-preview-container">
          <button id="preview-icon" class="btn btn-icon" style="width: 46px; height: 46px; border-radius: 50%; display: flex; justify-content: center; align-items: center; border: 1px solid <?php echo $config->ai->miniPrograms->themeList[$iconTheme][1]; ?>; background-color: <?php echo $config->ai->miniPrograms->themeList[$iconTheme][0]; ?>">
            <?php echo $config->ai->miniPrograms->iconList[$iconName]; ?>
          </button>
        </div>
        <div class="icon-setting-container">
          <div>
            <header style="margin-bottom: 12px;"><?php echo $lang->ai->miniPrograms->customBackground; ?></header>
            <div id="theme-buttons" style="display: flex; gap: 20px; width: 400px;">
              <?php
                foreach($config->ai->miniPrograms->themeList as $theme)
                {
                  if($config->ai->miniPrograms->themeList[$iconTheme][0] === $theme[0]) echo "<button type='button' class='btn btn-icon theme-checked' style='width: 32px; height: 32px; border-radius: 50%; background-color: $theme[0]; border: 1px solid $theme[1];'>{$config->ai->miniPrograms->iconCheck}</button>";
                  echo "<button type='button' class='btn btn-icon' style='width: 32px; height: 32px; border-radius: 50%; background-color: $theme[0]; border: 1px solid $theme[1];'></button>";
                }
              ?>

            </div>
          </div>
          <div style="margin-top: 32px;">
            <header style="margin-bottom: 12px;"><?php echo $lang->ai->miniPrograms->customIcon; ?></header>
            <div id="icon-buttons" style="display: grid; column-gap: 20px; row-gap: 16px; grid-template-columns: repeat(8, 1fr); justify-items: center; align-items: center;">
              <?php foreach($config->ai->miniPrograms->iconList as $name => $icon) echo $icon; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="display: flex; justify-content: center; border-top: none;">
        <button type="button" class="btn btn-wide btn-primary" id="save-icon-button" data-dismiss="modal"><?php echo $lang->save; ?></button>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
