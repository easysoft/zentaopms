<?php
/**
 * The create struct view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Thanatos <thanatos@915.com>
 * @package     doc
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'vue/vue.js');?>
<?php js::set('paramsTypeOption', $lang->api->paramsScopeOptions);?>
<?php
js::set('typeOptions', $typeOptions);
js::set('structAdd', $lang->struct->add);
js::set('addSubField', $lang->struct->addSubField);
js::set('structDelete', $lang->delete);
js::set('langField', $lang->struct->field);
js::set('langDesc', $lang->struct->desc);
js::set('struct_field', $lang->struct->field);
js::set('struct_desc', $lang->struct->desc);
js::set('struct_action', $lang->struct->action);
js::set('struct_required', $lang->struct->required);
js::set('struct_paramsType', $lang->struct->paramsType);
js::set('struct', '');
?>
<div id="app">
  <div id="mainContent" class="main-content">
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->api->createStruct;?></h2>
      </div>
      <form class="load-indicator main-form form-ajax" id="dataform" method='post' enctype='multipart/form-data'>
        <table class='table table-form'>
          <tbody>
            <tr>
              <th class='w-110px'><?php echo $lang->api->structName;?></th>
              <td><?php echo html::input('name', '', "class='form-control' required");?> </td>
              <td></td>
            </tr>
            <tr>
              <td class="w-110px"></td>
              <td colspan="2">
                <body-field @change="changeAttr"></body-field>
                <input type="hidden" name="attribute" v-model="attr">
              </td>
            </tr>
            <tr>
              <th class='w-110px'><?php echo $lang->api->desc;?></th>
              <td colspan="2"><?php echo html::textarea('desc', '', "class='form-control'");?></td>
            </tr>
            <tr>
              <td colspan='3' class='text-center form-actions'>
                <?php echo html::submitButton();?>
                <?php if(empty($gobackLink)) echo html::backButton($lang->goback, "data-app='{$app->tab}'");?>
                <?php if(!empty($gobackLink)) echo html::a($gobackLink, $lang->goback, '', "class='btn btn-back btn-wide'");?>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
