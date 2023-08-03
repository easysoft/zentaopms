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
  <?php foreach($roleTemplates as $role):?>
    <div id="role-template-<?php echo $role->id;?>"  data-id="<?php echo $role->id;?>" class="role-template-card" style="border: 1px solid #D8DBDE; border-radius: 4px; padding: 12px; width: 100%;">
      <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px;">
        <p id="role-<?php echo $role->id;?>" class="role" style="overflow: hidden; white-space: nowrap; text-overflow: clip;" title="<?php echo $role->role; ?>"><?php echo $role->role; ?></p>
        <div style="display: flex; gap: 2px;">
          <?php echo html::commonButton("<span class='text-primary'>{$lang->app->common}</span>", "data-action='apply'", 'btn btn-link'); ?>
          <?php echo html::commonButton("<i class='icon icon-edit icon-sm text-primary'></i>", "data-action='edit'", 'btn btn-link'); ?>
          <?php echo html::commonButton("<i class='icon icon-trash icon-sm text-primary'></i>", "data-action='del'", 'btn btn-link'); ?>
        </div>
      </div>
      <p id="characterization-<?php echo $role->id;?>" class="characterization text-gray" style="overflow: hidden; white-space: nowrap; text-overflow: clip; padding: 4px 0;" title="<?php echo $role->characterization;?>"><?php echo $role->characterization; ?></p>
    </div>
  <?php endforeach;?>
</div>
