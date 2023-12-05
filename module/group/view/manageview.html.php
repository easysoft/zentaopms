<?php
/**
 * The manage view by group view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     group
 * @version     $Id: managepriv.html.php 1517 2011-03-07 10:02:57Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2 title='<?php echo $group->name;?>'>
      <span id='groupName'><i class='icon-lock'></i> <?php echo $group->name;?></span>
      <small> <?php echo $lang->arrow . $lang->group->manageView;?></small>
    </h2>
  </div>
  <form class="load-indicator main-form form-ajax" id="manageViewForm" method="post" target='hiddenwin'>
    <table class='table table-form'>
      <tr>
        <th class='text-bottom thWidth'><?php echo $lang->group->viewList;?></th>
        <td class='text-bottom'>
          <?php foreach($lang->mainNav as $menuKey => $menu):?>
          <?php if(!is_string($menu)) continue;?>
          <?php list($moduleName, $module) = explode('|', $menu);?>
          <?php if($menuKey == 'my') continue;?>
          <?php $moduleName = strip_tags($moduleName);?>
          <div class='group-item'>
            <div class='checkbox-primary'>
              <input type='checkbox' id='<?php echo $menuKey?>' name='actions[views][<?php echo strtolower($menuKey);?>]' value='<?php echo $menuKey;?>' <?php if(isset($group->acl['views'][$menuKey]) or empty($group->acl['views'])) echo "checked";?> />
              <label class='priv' for='<?php echo $menuKey?>'>
                <?php echo $moduleName;?>
              </label>
            </div>
          </div>
        <?php endforeach;?>
          <div class='group-item'>
            <div class='checkbox-primary'>
              <input type="checkbox" id='allchecker' name="allchecker" onclick="selectAll(this)" <?php if(empty($group->acl['views'])) echo "checked";?> />
              <label class='priv' for='allchecker'>
                <?php echo $lang->selectAll?>
              </label>
            </div>
          </div>
        </td>
      </tr>
      <?php if($this->config->systemMode == 'ALM'):?>
      <tr id='programBox' style='display:none'>
        <th class='text-right'><?php echo $lang->group->programList?></th>
        <td>
          <?php if($programs):?>
          <div class='input-group'>
            <?php echo html::select("actions[programs][]", $programs, isset($group->acl['programs']) ? join(',', $group->acl['programs']) : '', "class='form-control picker-select' multiple")?>
            <span class='input-group-addon strong'><?php echo $lang->group->noticeVisit?></span>
          </div>
          <?php else:?>
          <?php echo $lang->group->noneProgram;?>
          <?php endif;?>
        </td>
      </tr>
      <?php endif;?>
      <tr id='productBox' style='display:none'>
        <th class='text-right'><?php echo $lang->group->productList?></th>
        <td>
          <?php if($products):?>
          <div class='input-group'>
            <?php echo html::select("actions[products][]", $products, isset($group->acl['products']) ? join(',', $group->acl['products']) : '', "class='form-control picker-select' multiple")?>
            <span class='input-group-addon strong'><?php echo $lang->group->noticeVisit?></span>
          </div>
          <?php else:?>
          <?php echo $lang->group->noneProduct;?>
          <?php endif;?>
        </td>
      </tr>
      <tr id='projectBox' style='display:none'>
        <th class='text-right'><?php echo $lang->group->projectList?></th>
        <td>
          <?php if($projects):?>
          <div class='input-group'>
            <?php echo html::select("actions[projects][]", $projects, isset($group->acl['projects']) ? join(',', $group->acl['projects']) : '', "class='form-control picker-select' data-dropDirection='bottom' multiple")?>
            <span class='input-group-addon strong'><?php echo $lang->group->noticeVisit?></span>
          </div>
          <?php else:?>
          <?php echo $lang->group->noneProject;?>
          <?php endif;?>
        </td>
      </tr>
      <tr id='executionBox' style='display:none'>
        <th class='text-right'><?php echo $lang->group->executionList;?></th>
        <td>
          <?php if($executions):?>
          <div class='input-group'>
            <?php echo html::select("actions[sprints][]", $executions, isset($group->acl['sprints']) ? join(',', $group->acl['sprints']) : '', "class='form-control picker-select' data-dropDirection='bottom' multiple")?>
            <span class='input-group-addon strong'><?php echo $lang->group->noticeVisit?></span>
          </div>
          <?php else:?>
          <?php echo $lang->group->noneExecution;?>
          <?php endif;?>
        </td>
      </tr>
      <tr>
        <th class='text-right text-top'><?php echo $lang->group->dynamic?></th>
        <td class='pl-0px pt-0px'>
          <table class='table table-form'>
            <?php foreach($lang->mainNav as $module => $title):?>
            <?php if(!is_string($title)) continue;?>
            <?php
            /* Ignore null actions menus. */
            $isNullActions = true;
            if(isset($lang->action->dynamicAction->$module)) $isNullActions = false;
            if(isset($navGroup[$module]) and $isNullActions)
            {
                foreach($navGroup[$module] as $subModule)
                {
                    if(isset($lang->action->dynamicAction->$subModule))
                    {
                        $isNullActions = false;
                        break;
                    }
                }
            }
            if($isNullActions) continue;
            ?>
            <tr id='<?php echo "{$module}ActionBox";?>'>
              <th class='w-100px text-left text-top'>
                <div class='action-item'>
                  <div class='checkbox-primary'>
                    <input type="checkbox" id='allchecker' onclick="selectAll(this)"/>
                    <label class='priv' for='allchecker'><?php echo substr($title, 0, strpos($title, '|'));?></label>
                  </div>
                </div>
              </th>
              <td>
                <?php if(isset($lang->action->dynamicAction->$module)):?>
                <div class='clearfix'>
                  <?php foreach($lang->action->dynamicAction->$module as $action => $actionTitle):?>
                  <div class='action-item'>
                    <div class='checkbox-primary'>
                      <input type='checkbox' id='<?php echo "$module-$action";?>' name='actions[actions][<?php echo $module;?>][<?php echo $action;?>]' value='<?php echo $action;?>' <?php if(isset($group->acl['actions'][$module][$action]) or !isset($group->acl['actions'])) echo "checked";?> />
                      <label class='priv' for='<?php echo "$module-$action";?>'><?php echo $actionTitle;?></label>
                    </div>
                  </div>
                  <?php endforeach;?>
                </div>
                <?php endif;?>
                <?php if(isset($navGroup[$module])):?>
                <?php foreach($navGroup[$module] as $subModule):?>
                <?php if(isset($lang->action->dynamicAction->$subModule)):?>
                <div class='clearfix'>
                  <?php foreach($lang->action->dynamicAction->$subModule as $action => $actionTitle):?>
                  <div class='action-item'>
                    <div class='checkbox-primary'>
                      <input type='checkbox' id='<?php echo "$subModule-$action";?>' name='actions[actions][<?php echo $subModule;?>][<?php echo $action;?>]' value='<?php echo $action;?>' <?php if(isset($group->acl['actions'][$subModule][$action]) or !isset($group->acl['actions'])) echo "checked";?> />
                      <label class='priv' for='<?php echo "$subModule-$action";?>'><?php echo $actionTitle;?></label>
                    </div>
                  </div>
                  <?php endforeach;?>
                </div>
                <?php endif;?>
                <?php endforeach;?>
                <?php endif;?>
              </td>
            </tr>
            <?php endforeach;?>
          </table>
        </td>
      </tr>
      <tr>
        <td colspan='2' class='form-actions text-center'>
          <?php echo html::submitButton();?>
          <?php echo html::backButton();?>
          <?php echo html::hidden('foo'); // Just a hidden var, to make sure $_POST is not empty.?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
