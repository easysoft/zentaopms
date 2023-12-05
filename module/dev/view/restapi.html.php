<?php
/**
 * The index view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
include 'header.html.php';
js::set('confirmDelete', $lang->api->confirmDelete);
?>
<div class="main-row" id="mainContent">
  <div class='side-col' id='sidebar'>
    <div class='cell module-tree'>
      <div class="panel panel-sm with-list">
        <div class='panel-heading'><i class='icon-list'></i> <strong><?php echo $lang->dev->moduleList?></strong></div>
        <?php echo $moduleTree;?>
      </div>
    </div>
  </div>
  <div class="main-col main-content module-col">
    <div class="main-col module-content">
      <div class="cell" id="content">
        <div class="no-padding">
          <div class="detail-title no-padding doc-title">
            <div class="http-method label"><?php echo $api->method;?></div>
            <div class="path" title="<?php echo $api->path;?>"><?php echo $api->path;?></div>
          </div>
        </div>
        <div>
          <h2 class="title" title="<?php echo $api->title;?>"><?php echo $api->title;?></h2>
          <div class="desc"><?php echo $api->desc;?></div>
          <?php if($api->params['header']):?>
          <h3 class="title"><?php echo $lang->api->header;?></h3>
          <table class="table table-data paramsTable">
            <thead>
            <tr>
              <th><?php echo $lang->api->req->name;?></th>
              <th><?php echo $lang->api->req->type;?></th>
              <th><?php echo $lang->api->req->required;?></th>
              <th><?php echo $lang->api->req->desc;?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($api->params['header'] as $param):?>
            <tr>
              <td><?php echo $param['field'];?></td>
              <td>String</td>
              <td><?php echo $lang->api->boolList[$param['required']];?></td>
              <td><?php echo $param['desc'];?></td>
            <tr>
            <?php endforeach;
            ;?>
            </tbody>
          </table>
          <?php endif;?>
          <?php if($api->params['query']):?>
          <h3 class="title"><?php echo $lang->api->query;?></h3>
          <table class="table table-data paramsTable">
            <thead>
            <tr>
              <th><?php echo $lang->api->req->name;?></th>
              <th><?php echo $lang->api->req->type;?></th>
              <th><?php echo $lang->api->req->required;?></th>
              <th><?php echo $lang->api->req->desc;?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($api->params['query'] as $param):?>
            <tr>
              <td><?php echo $param['field'];?></td>
              <td>String</td>
              <td><?php echo $lang->api->boolList[$param['required']];?></td>
              <td><?php echo $param['desc'];?></td>
            <tr>
            <?php endforeach;
            ;?>
            </tbody>
          </table>
          <?php endif;?>
          <?php
          function parseTree($data, $typeList, $level = 0)
          {
              global $lang;

              $str   = '<tr>';
              $field = '';
              for($i = 0; $i < $level; $i++) $field .= '&nbsp;&nbsp;'. ($i == $level-1 ? '∟' : '&nbsp;') . '&nbsp;&nbsp;';
              $field .= $data['field'];
              $str   .= '<td>' . $field . '</td>';
              $str   .= '<td>' . zget($typeList, $data['paramsType'], '') . '</td>';
              $str   .= '<td class="text-center">' . zget($lang->api->boolList, $data['required'], '') . '</td>';
              $str   .= '<td>' . $data['desc'] . '</td>';
              $str   .= '</tr>';
              if(isset($data['children']) && count($data['children']) > 0)
              {
                  $level++;
                  foreach($data['children'] as $item) $str .= parseTree($item, $typeList, $level);
              }
              return $str;
          }
          ?>
          <?php if($api->params['params']):?>
          <h3 class="title"><?php echo $lang->api->params;?></h3>
          <table class="table table-data paramsTable">
            <thead>
            <tr>
              <th><?php echo $lang->api->req->name;?></th>
              <th class="w-50px"><?php echo $lang->api->req->type;?></th>
              <th class="w-50px text-center"><?php echo $lang->api->req->required;?></th>
              <th class="w-300px"><?php echo $lang->api->req->desc;?></th>
            </tr>
            </thead>
            <tbody><?php foreach($api->params['params'] as $item) echo parseTree($item, $typeList);?></tbody>
          </table>
          <?php endif;?>
          <?php if($api->paramsExample):?>
          <h3 class="title"><?php echo $lang->api->paramsExample;?></h3>
          <pre><code><?php echo $api->paramsExample;?></code></pre>
          <?php endif;?>
          <?php if($api->response):?>
          <h3 class="title"><?php echo $lang->api->response;?></h3>
          <table class="table table-data">
            <thead>
            <tr>
              <th><?php echo $lang->api->req->name;?></th>
              <th class="w-50px"><?php echo $lang->api->req->type;?></th>
              <th class="w-50px text-center"><?php echo $lang->api->req->required;?></th>
              <th class="w-300px"><?php echo $lang->api->req->desc;?></th>
            </tr>
            </thead>
            <tbody>
              <?php foreach($api->response as $item) echo parseTree($item, $typeList);?>
            </tbody>
          </table>
          <?php endif;?>
          <?php if($api->responseExample):?>
          <h3 class='title'><?php echo $lang->api->responseExample;?></h3>
          <pre><code><?php echo $api->responseExample;?></code></pre>
          <?php endif;?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
