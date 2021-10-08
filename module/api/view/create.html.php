<?php
/**
 * The create view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: create.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<?php include '../../common/view/kindeditor.html.php'; ?>
<?php js::set('example', $example); ?>
<?php js::set('libID', $libID); ?>
  <div class='modal fade' id='filterStruct'>
    <div class='modal-dialog mw-500px'>
      <div class='modal-content'>
        <div class='modal-header'>
          <button type='button' class='close' data-dismiss='modal'>
            <i class='icon icon-close'></i>
          </button>
          <h4 class='modal-title'><?php echo $lang->api->struct; ?></h4>
        </div>
        <div class='modal-body'>
          <table class='table table-form'>
            <tbody>
              <tr>
                <td> <?php echo html::select('filter', $allStruct, '', "class='form-control chosen filterSelect'"); ?> </td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <?php echo html::submitButton($lang->confirm, '', 'btn btn-wide btn-primary submit-filter'); ?>
        </div>
      </div>
    </div>
  </div>
  <div id="mainContent" class="main-content">
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->api->create; ?></h2>
      </div>
      <form class="load-indicator main-form form-ajax" id="dataform" method='post' enctype='multipart/form-data'>
        <table class='table table-form'>
          <tbody>
            <tr>
              <th class='w-110px'><?php echo $lang->api->lib; ?></th>
              <td> <?php echo html::select('lib', $libs, $libID, "class='form-control chosen' onchange=loadDocModule(this.value)"); ?> </td>
              <td></td>
            </tr>
            <tr>
              <th><?php echo $lang->api->module; ?></th>
              <td>
            <span
                id='moduleBox'><?php echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control chosen'"); ?></span>
              </td>
              <td></td>
            </tr>
            <tr>
              <th><?php echo $lang->api->formTitle; ?></th>
              <td colspan='2'><?php echo html::input('title', '', "class='form-control' required"); ?></td>
            </tr>
            <tr>
              <th><?php echo $lang->api->path; ?></th>
              <td colspan='2'>
                <div class='table-row'>
                  <div class='table-col col-prefix'>
                    <?php echo html::select('protocol', $lang->api->protocalOptions, 'HTTP', "class='form-control chosen'"); ?>
                  </div>
                  <div class='table-col col-prefix'>
                    <?php echo html::select('method', $lang->api->methodOptions, 'GET', "class='form-control chosen'"); ?>
                  </div>
                  <div class='table-col'>
                    <?php echo html::input('path', '', "class='form-control'"); ?>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->api->requestType; ?></th>
              <td><span id='moduleBox'><?php echo html::select('requestType', $lang->api->requestTypeOptions, 'application/json', "class='form-control chosen'"); ?></span></td>
            </tr>
            <tr>
              <th><?php echo $lang->api->status; ?></th>
              <td><?php echo html::radio('status', $lang->api->statusOptions, apiModel::STATUS_DONE); ?></td>
            </tr>
            <tr>
              <th>
                <nobr><?php echo $lang->api->owner; ?></nobr>
              </th>
              <td>
                <div class='input-group'>
                  <?php echo html::select('owner', $allUsers, $user, "class='form-control chosen'"); ?>
                </div>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->api->params; ?></th>
              <td colspan='2' id='paramDiv'>
                <div class="row row-no-gutters col-custom">
                  <div class='col-md-3'>
                    <div class="table-row">
                      <span class='input-group-addon w-50px'><?php echo $lang->api->field; ?></span>
                      <?php echo html::input("params[0][field]", '', "class='form-control'"); ?>
                    </div>
                  </div>
                  <div class='col-md-3'>
                    <div class="table-row">
                      <span class='input-group-addon w-70px'><?php echo $lang->api->required; ?></span>
                      <?php echo html::select('params[0][required]', $lang->api->requiredOptions, 0, "class='form-control'"); ?>
                    </div>
                  </div>
                  <div class='col-md-3'>
                    <div class="table-row">
                      <span class='input-group-addon w-50px'><?php echo $lang->api->scope; ?></span>
                      <?php echo html::select('params[0][scope]', $lang->api->paramsScopeOptions, '', "class='form-control' onchange='loadParamsTypeOptions(this);'"); ?>
                    </div>
                  </div>
                  <div class='col-md-3'>
                    <div class="table-row">
                      <span class='input-group-addon w-50px'><?php echo $lang->api->paramsType; ?></span>
                      <?php echo html::select('params[0][paramsType]', $lang->api->paramsTypeOptions, '', "class='form-control' onchange='changeType(this, \'paramsType\');'"); ?>
                    </div>
                  </div>
                  <div class='col-md-3 typeCustom hidden'>
                    <div class="table-row">
                      <span class='input-group-addon w-70px'><?php echo $lang->api->struct; ?></span>
                      <?php echo html::select("params[0][ref]", '', '', "class='form-control'"); ?>
                    </div>
                  </div>
                  <div class='col-md-3'>
                    <div class="table-row">
                      <span class='input-group-addon w-60px'><?php echo $lang->api->default; ?></span>
                      <?php echo html::input('params[0][default]', '', "class='form-control'"); ?>
                    </div>
                  </div>
                  <div class='col-md-3'>
                    <div class="table-row">
                      <span class='input-group-addon w-50px'><?php echo $lang->api->desc; ?></span>
                      <?php echo html::textarea('params[0][desc]', '', "class='form-control' style='height:32px'"); ?>
                    </div>
                  </div>
                  <div class='col-md-3'>
                    <span class='input-group-addon w-40px'><a onclick='addItem(this);'><i class='icon icon-plus'></i></a></span>
                    <span class='input-group-addon w-40px'><a onclick='deleteItem(this)'><i class='icon icon-close'></i></a></span>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <th>
                <nobr><?php echo $lang->api->paramsExample; ?></nobr>
              </th>
              <td>
                <div class='input-group'>
                  <?php echo html::textarea('paramsExample', '', "style='width:100%;height:200px'"); ?>
                </div>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->api->response; ?></th>
              <td colspan='2' id='responseDiv'>
                <div class="row row-no-gutters col-custom">
                  <div class='col-md-3'>
                    <div class="table-row">
                      <span class='input-group-addon w-50px'><?php echo $lang->api->res->name; ?></span>
                      <?php echo html::input("response[0][name]", '', "class='form-control'"); ?>
                    </div>
                  </div>
                  <div class='col-md-3'>
                    <div class="table-row">
                      <span class='input-group-addon w-50px'><?php echo $lang->api->res->desc; ?></span>
                      <?php echo html::input("response[0][desc]", '', "class='form-control'"); ?>
                    </div>
                  </div>
                  <div class='col-md-3'>
                    <div class="table-row">
                      <span class='input-group-addon w-50px'><?php echo $lang->api->res->type; ?></span>
                      <?php echo html::select('response[0][type]', $lang->api->allParamsTypeOptions, '', "class='form-control' onchange='changeType(this, \"type\");'"); ?>
                    </div>
                  </div>
                  <div class='col-md-3 typeCustom hidden'>
                    <div class="table-row">
                      <span class='input-group-addon w-70px'><?php echo $lang->api->struct; ?></span>
                      <?php echo html::select("response[0][ref]", '', '', "class='form-control'"); ?>
                    </div>
                  </div>
                  <div class='col-md-3'>
                    <span class='input-group-addon w-40px'><a onclick='addResponseItem(this);'><i class='icon icon-plus'></i></a></span>
                    <span class='input-group-addon w-40px'><a onclick='deleteResponseItem(this)'><i class='icon icon-close'></i></a></span>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <th>
                <nobr><?php echo $lang->api->responseExample; ?></nobr>
              </th>
              <td>
                <div class='input-group'>
                  <?php echo html::textarea('responseExample', '', "style='width:100%;height:200px'"); ?>
                </div>
              </td>
            </tr>
            <tr id='contentBox'>
              <th><?php echo $lang->api->desc; ?></th>
              <td colspan='2'>
                <div class='contenthtml'><?php echo html::textarea('desc', '', "style='width:100%;height:200px'"); ?></div>
              </td>
            </tr>
            <tr>
              <td colspan='3' class='text-center form-actions'>
                <?php echo html::submitButton(); ?>
                <?php if(empty($gobackLink)) echo html::backButton($lang->goback, "data-app='{$app->tab}'"); ?>
                <?php if(!empty($gobackLink)) echo html::a($gobackLink, $lang->goback, '', "class='btn btn-back btn-wide'"); ?>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
<?php js::set('noticeAcl', $lang->noticeAcl); ?>
<?php include '../../common/view/footer.html.php'; ?>
