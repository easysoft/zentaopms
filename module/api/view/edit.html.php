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
    <div class='modal fade' id='customType'>
        <div class='modal-dialog mw-500px'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal'><i class='icon icon-close'></i></button>
                    <h4 class='modal-title'><?php echo $lang->api->customType; ?></h4>
                </div>
                <div class='modal-body'>
                    <textarea class="form-control customTypeTextarea" rows="10" placeholder=""></textarea>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-wide btn-warning formatCustom" style="float: left"><?php echo $lang->api->format; ?></button>
                    <?php echo html::submitButton($lang->confirm, '', 'btn btn-wide btn-primary submit-custom'); ?>
                </div>
            </div>
        </div>
    </div>
    <div id="mainContent" class="main-content">
        <div class='center-block'>
            <div class='main-header'>
                <?php if($edit): ?>
                    <h2>
                        <span class='label label-id'><?php echo $api->id; ?></span>
                        <?php echo html::a($this->createLink('api', 'index', "apiID=$api->id"), $api->title, '', "title='$api->title'"); ?>
                        <small> <?php echo $lang->arrow . ' ' . $lang->api->edit; ?></small>
                    </h2>
                    <div class='pull-right'><?php echo html::a('###', $lang->save, '', 'id="top-submit" class="btn btn-primary"'); ?></div>
                <?php else: ?>
                    <h2><?php echo $lang->api->create; ?></h2>
                <?php endif; ?>
            </div>
            <form class="load-indicator main-form form-ajax" id="dataform" method='post' enctype='multipart/form-data'>
                <table class='table table-form'>
                    <tbody>
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
                        <td colspan='2'><?php echo html::input('title', $api->title, "class='form-control' required"); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->api->path; ?></th>
                        <td colspan='2'><?php echo html::input('path', $api->path, "class='form-control'"); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->api->protocol; ?></th>
                        <td><?php echo html::radio('protocol', $lang->api->protocalOptions, $api->protocol ? $api->protocol : 'HTTP'); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->api->method; ?></th>
                        <td>
            <span
                id='moduleBox'><?php echo html::select('method', $lang->api->methodOptions, $api->method ? $api->method : 'GET', "class='form-control chosen'"); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->api->requestType; ?></th>
                        <td>
            <span
                id='moduleBox'><?php echo html::select('requestType', $lang->api->requestTypeOptions, $api->requestType ? $api->requestType : 'application/json', "class='form-control chosen'"); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->api->status; ?></th>
                        <td><?php echo html::radio('status', $lang->api->statusOptions, $api->status ? $api->status : apiModel::STATUS_DOING); ?></td>
                    </tr>
                    <tr>
                        <th>
                            <nobr><?php echo $lang->api->owner; ?></nobr>
                        </th>
                        <td>
                            <div class='input-group'>
                                <?php echo html::select('owner', $allUsers, $api->owner ? $api->owner : $user, "class='form-control chosen'"); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->api->params; ?></th>
                        <td colspan='2' id='paramDiv'>
                            <?php
                            $params = $api->params ? $api->params : ['']
                            ?>
                            <?php foreach($params as $param): ?>
                                <div class="row row-no-gutters col-custom">
                                    <div class='col-md-3 col-lg-2'>
                                        <div class="table-row">
                                            <span class='input-group-addon w-50px'><?php echo $lang->api->field; ?></span>
                                            <?php echo html::input("params[0][field]", $param['field'], "class='form-control'"); ?>
                                        </div>
                                    </div>
                                    <div class='col-md-3 col-lg-2'>
                                        <div class="table-row">
                                        <span
                                            class='input-group-addon w-70px'><?php echo $lang->api->required; ?></span>
                                            <?php echo html::select('params[0][required]', $lang->api->requiredOptions, $param['required'] ? $param['required'] : 0, "class='form-control'"); ?>
                                        </div>
                                    </div>
                                    <div class='col-md-3 col-lg-2'>
                                        <div class="table-row">
                                            <span class='input-group-addon w-50px'><?php echo $lang->api->scope; ?></span>
                                            <?php echo html::select('params[0][scope]', $lang->api->paramsScopeOptions, $param['scope'], "class='form-control' onchange='loadParamsTypeOptions(this);'"); ?>
                                        </div>
                                    </div>
                                    <div class='col-md-3 col-lg-2'>
                                        <div class="table-row">
                                        <span
                                            class='input-group-addon w-50px'><?php echo $lang->api->paramsType; ?></span>
                                            <?php $typeOpt = $param['scope'] == apiModel::SCOPE_BODY ? $lang->api->allParamsTypeOptions : $lang->api->paramsTypeOptions ?>
                                            <?php echo html::select('params[0][paramsType]', $typeOpt, $param['paramsType'], "class='form-control' onchange='changeType(this);'"); ?>
                                        </div>
                                    </div>
                                    <div class='col-md-3 col-lg-2 typeCustom <?php echo $param['paramsType'] == apiModel::PARAMS_TYPE_CUSTOM ? '' : 'hidden' ?>'>
                                        <div class="table-row">
                                            <input type="hidden" class="custom" name="params[0][custom]" value="<?php echo htmlspecialchars($param['custom']) ?>">
                                            <button type="button" data-toggle="modal" data-target="#customType"
                                                    class="btn btn-wide customType" style="width: 80px;background: #eee">
                                                <?php echo $lang->api->customType ?>
                                            </button>
                                        </div>
                                    </div>
                                    <div class='col-md-3 col-lg-2'>
                                        <div class="table-row">
                                            <span class='input-group-addon w-60px'><?php echo $lang->api->default; ?></span>
                                            <?php echo html::input('params[0][default]', $param->default, "class='form-control'"); ?>
                                        </div>
                                    </div>
                                    <div class='col-md-3 col-lg-2'>
                                        <div class="table-row">
                                            <span class='input-group-addon w-50px'><?php echo $lang->api->desc; ?></span>
                                            <?php echo html::textarea('params[0][desc]', $param->desc, "class='form-control' style='height:32px'"); ?>
                                        </div>
                                    </div>
                                    <div class='col-md-3 col-lg-2'>
                                        <span class='input-group-addon w-40px'><a onclick='addItem(this);'><i class='icon icon-plus'></i></a></span>
                                        <span class='input-group-addon w-40px'><a onclick='deleteItem(this)'><i class='icon icon-close'></i></a></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->api->response; ?></th>
                        <td colspan='2' id='paramDiv'>
                            <div class="row row-no-gutters col-custom">
                                <div class='col-md-3 col-lg-2'>
                                    <div class="table-row">
                                        <span class='input-group-addon w-50px'><?php echo $lang->api->res->name; ?></span>
                                        <?php echo html::input("response[name]", $api->response['name'], "class='form-control'"); ?>
                                    </div>
                                </div>
                                <div class='col-md-3 col-lg-2'>
                                    <div class="table-row">
                                        <span class='input-group-addon w-50px'><?php echo $lang->api->res->desc; ?></span>
                                        <?php echo html::input("response[desc]", $api->response['desc'], "class='form-control'"); ?>
                                    </div>
                                </div>
                                <div class='col-md-3 col-lg-2'>
                                    <div class="table-row">
                                        <span
                                            class='input-group-addon w-50px'><?php echo $lang->api->res->type; ?></span>
                                        <?php echo html::select('response[type]', $lang->api->allParamsTypeOptions, $api->response['type'], "class='form-control' onchange='changeType(this);'"); ?>
                                    </div>
                                </div>
                                <div class='col-md-3 col-lg-2 typeCustom <?php echo $api->response['type'] == apiModel::PARAMS_TYPE_CUSTOM ? '' : 'hidden' ?>'>
                                    <div class="table-row">
                                        <input type="hidden" class="custom" name="response[custom]" value="<?php echo htmlspecialchars($api->response['custom']) ?>">
                                        <button type="button" data-toggle="modal" data-target="#customType"
                                                class="btn btn-wide customType" style="width: 80px;background: #eee">
                                            <?php echo $lang->api->customType ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr id='contentBox'>
                        <th><?php echo $lang->api->desc; ?></th>
                        <td colspan='2'>
                            <div class='contenthtml'><?php echo html::textarea('desc', $api->desc, "style='width:100%;height:200px'"); ?></div>
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