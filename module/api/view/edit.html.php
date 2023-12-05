<?php
/**
 * The create view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: create.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'vue/vue.js');?>
<?php js::set('libID', $api->lib);?>
<?php
js::set('typeOptions', $typeOptions);
js::set('langField', $lang->struct->field);
js::set('langDesc', $lang->struct->desc);
js::set('structAdd', $lang->struct->add);
js::set('structDelete', $lang->delete);
js::set('addSubField', $lang->struct->addSubField);
js::set('struct_field', $lang->struct->field);
js::set('struct_desc', $lang->struct->desc);
js::set('struct_action', $lang->struct->action);
js::set('struct_required', $lang->struct->required);
js::set('struct_paramsType', $lang->struct->paramsType);
js::set('api', $api);
?>
  <div class='modal fade' id='customType'>
    <div class='modal-dialog mw-500px'>
      <div class='modal-content'>
        <div class='modal-header'>
          <button type='button' class='close' data-dismiss='modal'>
            <i class='icon icon-close'></i>
          </button>
          <h4 class='modal-title'><?php echo $lang->api->customType;?></h4>
        </div>
        <div class='modal-body'>
          <textarea class="form-control customTypeTextarea" rows="10" placeholder=""></textarea>
        </div>
        <div class="modal-footer">
          <button class="btn btn-wide btn-warning formatCustom" style="float: left"><?php echo $lang->api->format;?></button>
          <?php echo html::submitButton($lang->confirm, '', 'btn btn-wide btn-primary submit-custom');?>
        </div>
      </div>
    </div>
  </div>
  <div id="mainContent" class="main-content">
    <div class='center-block' id="apiApp">
      <div class='main-header'>
        <?php if($edit):?>
          <h2>
            <span class='label label-id'><?php echo $api->id;?></span>
            <?php echo html::a($this->createLink('api', 'index', "apiID=$api->id"), $api->title, '', "title='$api->title'");?>
            <small> <?php echo $lang->arrow . ' ' . $lang->api->edit;?></small>
          </h2>
          <div
              class='pull-right'><?php echo html::a('###', $lang->save, '', 'id="top-submit" class="btn btn-primary"');?></div>
        <?php else:?>
          <h2><?php echo $lang->api->create;?></h2>
        <?php endif;?>
      </div>
      <form class="load-indicator main-form form-ajax" id="dataform" method='post' enctype='multipart/form-data'>
        <table class='table table-form'>
          <tbody>
            <tr>
              <th><?php echo $lang->api->module;?></th>
              <td>
            <span
                id='moduleBox'><?php echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></span>
              </td>
              <td></td>
            </tr>
            <tr>
              <th><?php echo $lang->api->formTitle;?></th>
              <td colspan='2'><?php echo html::input('title', $api->title, "class='form-control' required");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->api->path;?></th>
              <td colspan='2'><?php echo html::input('path', $api->path, "class='form-control'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->api->protocol;?></th>
              <td><?php echo html::radio('protocol', $lang->api->protocalOptions, $api->protocol ? $api->protocol : 'HTTP');?></td>
            </tr>
            <tr>
              <th><?php echo $lang->api->method;?></th>
              <td><span id='moduleBox'><?php echo html::select('method', $lang->api->methodOptions, $api->method ? $api->method : 'GET', "class='form-control chosen'");?></span></td>
            </tr>
            <tr>
              <th><?php echo $lang->api->requestType;?></th>
              <td><span id='moduleBox'><?php echo html::select('requestType', $lang->api->requestTypeOptions, $api->requestType ? $api->requestType : 'application/json', "class='form-control chosen'");?></span></td>
            </tr>
            <tr>
              <th><?php echo $lang->api->status;?></th>
              <td><?php echo html::radio('status', $lang->api->statusOptions, $api->status ? $api->status : apiModel::STATUS_DOING);?></td>
            </tr>
            <tr>
              <th>
                <nobr><?php echo $lang->api->owner;?></nobr>
              </th>
              <td>
                <div class='input-group'>
                  <?php echo html::select('owner', $allUsers, $api->owner ? $api->owner : $user, "class='form-control chosen'");?>
                </div>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->api->header;?></th>
              <td colspan="2">
                <table class="table table-data">
                  <thead>
                    <tr>
                      <th class="w-300px"><?php echo $lang->struct->field;?></th>
                      <th class="w-50px"><?php echo $lang->struct->required;?></th>
                      <th class="w-500px"><?php echo $lang->struct->desc;?></th>
                      <th><?php echo $lang->struct->action;?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item,key) in header">
                      <td class="w-300px">
                        <input type="text" placeholder="<?php echo $lang->struct->field;?>" autocomplete="off" class="form-control" v-model="item.field">
                      </td>
                      <td class="w-50px">
                        <div class="checkbox-primary">
                          <input type="checkbox" v-model="item.required">
                          <label></label>
                        </div>
                      </td>
                      <td class="w-500px">
                        <input type="text" placeholder="<?php echo $lang->struct->desc;?>" autocomplete="off" class="form-control" v-model="item.desc">
                      </td>
                      <td>
                        <button class="btn btn-link btn-icon btn-add" type="button" @click="add(header, key, 'header')"><i class="icon icon-plus"></i></button>
                        <button class="btn btn-link btn-icon btn-delete" type="button" @click="del(header, key)"><i class="icon icon-close"></i></button>
                      </td>
                  </tbody>
                </table>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->api->query;?></th>
              <td colspan="2">
                <table class="table table-data">
                  <thead>
                    <tr>
                      <th class="w-300px"><?php echo $lang->struct->field;?></th>
                      <th class="w-50px"><?php echo $lang->struct->required;?></th>
                      <th class="w-500px"><?php echo $lang->struct->desc;?></th>
                      <th><?php echo $lang->struct->action;?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item,key) in queryP">
                      <td class="w-300px">
                        <input type="text" placeholder="<?php echo $lang->struct->field;?>" autocomplete="off" class="form-control" v-model="queryP[key].field">
                      </td>
                      <td class="w-50px">
                        <div class="checkbox-primary">
                          <input type="checkbox" v-model="item.required">
                          <label></label>
                        </div>
                      </td>
                      <td class="w-500px">
                        <input type="text" placeholder="<?php echo $lang->struct->desc;?>" autocomplete="off" class="form-control" v-model="item.desc">
                      </td>
                      <td>
                        <button class="btn btn-link btn-icon btn-add" type="button" @click="add(queryP, key, 'query')"><i class="icon icon-plus"></i></button>
                        <button class="btn btn-link btn-icon btn-delete" type="button" @click="del(queryP, key)"><i class="icon icon-close"></i></button>
                      </td>
                  </tbody>
                </table>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->api->params;?></th>
              <td colspan='2'>
                <body-field @change="changeAttr" :attr="attr" :struct-type="attrType" @change-type="changeType"></body-field>
                <input type="hidden" name="params" v-model="params">
              </td>
            </tr>
            <tr>
              <th>
                <nobr><?php echo $lang->api->paramsExample;?></nobr>
              </th>
              <td>
                <div class='input-group'>
                  <?php echo html::textarea('paramsExample', $api->paramsExample, "style='width:100%;height:200px'");?>
                </div>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->api->response;?></th>
              <td colspan='2' id='responseDiv'>
                <body-field @change="changeRes" :struct-type="'json'" :show-type="false" :attr="api.response"></body-field>
                <input type="hidden" name="response" v-model="response">
              </td>
            </tr>
            <tr>
              <th>
                <nobr><?php echo $lang->api->responseExample;?></nobr>
              </th>
              <td>
                <div class='input-group'>
                  <?php echo html::textarea('responseExample', $api->responseExample, "style='width:100%;height:200px'");?>
                </div>
              </td>
            </tr>
            <tr id='contentBox'>
              <th><?php echo $lang->api->desc;?></th>
              <td colspan='2'>
                <div class='contenthtml'><?php echo html::textarea('desc', $api->desc, "style='width:100%;height:200px'");?></div>
              </td>
            </tr>
            <tr>
              <td colspan='3' class='text-center form-actions'>
                <?php echo html::submitButton();?>
                <?php echo html::hidden('editedDate', $api->editedDate);?>
                <?php if(empty($gobackLink)) echo html::backButton($lang->goback, "data-app='{$app->tab}'");?>
                <?php if(!empty($gobackLink)) echo html::a($gobackLink, $lang->goback, '', "class='btn btn-back btn-wide'");?>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
<?php include '../../common/view/footer.html.php';?>
