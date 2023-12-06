<?php
/**
 * The custom install view file of instance module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   instance
 * @version   $Id$
 * @link      https://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('store', 'appView', "id={$appID}"), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <form class="cell load-indicator main-form form-ajax" method='post'>
    <h3><?php echo $lang->instance->customInstall;?></h3>
    <?php if(empty($components)):?>
    <div class="table-empty-tip">
      <p><?php echo html::a($this->createLink('store', 'appView', "id={$appID}"), $lang->instance->noComponent . '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-info'");?></p>
    </div>
    <?php endif?>
    <ul class="nav nav-tabs">
      <?php foreach($components as $component):?>
      <li class="<?php echo$activeTab == $component->name ? 'active' : '';?>">
        <a data-tab href="#<?php echo $component->name;?>"><?php echo $component->name;?></a>
      </li>
      <?php endforeach?>
    </ul>
    <div class="tab-content">
      <?php foreach($components as $component):?>
      <div class="tab-pane <?php echo$activeTab == $component->name ? 'active' : '';?>" id="<?php echo $component->name;?>">
        <table class="table table-form">
          <tbody>
            <?php foreach($component->settings as $setting):?>
            <?php
            if($setting->type == 'choice')
            {
                $selectOptions = array_combine($setting->options, $setting->options);
                if(strpos($setting->field, 'resources_cpu')    !== false) $selectOptions = $this->instance->getCpuOptions($setting->options);
                if(strpos($setting->field, 'resources_memory') !== false) $selectOptions = $this->instance->getMemOptions($setting->options);
            }
            ?>
            <tr>
              <th class='w-150px'><?php echo zget($this->lang->instance->componentFields, $setting->name, $setting->name);?></th>
              <td class='w-200px'>
                <div class='input-group'>
                  <?php if($setting->type == 'choice') echo html::select($setting->field, $selectOptions, $setting->value, "class='form-control chosen'");?>
                  <?php if($setting->type == 'int') echo html::number($setting->field, $setting->value, "class='form-control'", "require");?>
                </div>
              </td>
              <td></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
      <?php endforeach;?>
      <?php if(!empty($components)):?>
      <div class="text-center form-actions"><?php echo html::submitButton($lang->instance->install);?></div>
      <?php endif;?>
    </div>
  </form>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
