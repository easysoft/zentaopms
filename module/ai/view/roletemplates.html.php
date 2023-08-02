<?php
/**
 * The role template list component file of AI module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianyu Chen <chenjianyu@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<div id="roleList" style="display: flex; flex-direction: column; gap:8px;">
  <?php foreach($roleTemplates as $role)
  { ?>
    <div class="role-template-card" style="border: 1px solid #D8DBDE; border-radius: 4px; padding: 12px; width: 100%;">
      <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px;">
        <p id="role" class="clip"><?php echo $role->role; ?></p>
        <div style="display: flex; gap: 2px;">
          <?php echo html::commonButton("<span class='text-primary'>{$lang->app->common}</span>", '', 'btn btn-link'); ?>
          <?php echo html::commonButton("<i class='icon icon-edit icon-sm text-primary'></i>", "data-id=$role->id data-action='edit'", 'btn btn-link'); ?>
          <?php echo html::commonButton("<i class='icon icon-trash icon-sm text-primary'></i>", "data-id=$role->id data-action='del'", 'btn btn-link'); ?>
        </div>
      </div>
      <p id="characterization" class="text-gray clip"><?php echo $role->characterization; ?></p>
    </div>
  <?php } ?>
</div>
