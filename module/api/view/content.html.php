<div class="main-col" data-min-width="500">
  <div id="mainContent" class="main-row in">
    <div class="main-col col-8">
      <div class="cell" id="content">
        <div class="no-padding">
          <div class="detail-title no-padding doc-title">
            <div class="http-method label"><?php echo $api->method;?></div>
            <div class="path" title="<?php echo $api->path;?>"><?php echo $api->path;?></div>
            <div class="info">
              <div class="version">
                <div class='btn-group'>
                  <a href='javascript:;' class='btn btn-link btn-limit text-ellipsis' data-toggle='dropdown' style="max-width: 120px;">
                    #<?php echo $version ? $version : $api->version;?>
                    <span class="caret"></span>
                  </a>
                  <ul class='dropdown-menu api-version-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
                    <?php for($version = $api->version; $version > 0; $version--):?>
                    <li><a href='javascript:void(0)' data-url='<?php echo $this->createLink('api', 'index', "libID={$api->lib}&moduleID=0&apiID=$apiID&version=$version&release=$release");?>'>#<?php echo $version;?></a></li>
                    <?php endfor;?>
                  </ul>
                </div>
              </div>
            </div>
            <div class="actions">
              <?php
              echo html::a("javascript:fullScreen()", '<i class="icon-fullscreen"></i>', '', "title='{$lang->fullscreen}' class='btn btn-link fullscreen-btn'");
              if(!$isRelease)
              {
                if(common::hasPriv('api', 'edit')) echo html::a(inlink('edit', "apiID=$api->id"), '<i class="icon-edit"></i>', '', "title='{$lang->api->edit}' class='btn btn-link' data-app='{$this->app->tab}'");
                if(common::hasPriv('api', 'delete'))
                {
                  $deleteURL = $this->createLink('api', 'delete', "apiID=$api->id&confirm=yes");
                  echo html::a("javascript:ajaxDeleteApi(\"$deleteURL\", confirmDelete)", '<i class="icon-trash"></i>', '', "title='{$lang->api->delete}' class='btn btn-link'");
                }
              }
              ?>
            </div>
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
      <!-- 历史记录 -->
      <div class='cell'>
        <?php
        $canBeChanged = common::canBeChanged('api', $api);
        if($canBeChanged) $actionFormLink = $this->createLink('action', 'comment', "objectType=api&objectID=$api->id");?>
        <?php include '../../common/view/action.html.php';?>
      </div>
    </div>
    <div class="side-col col-2" id="sidebar">
      <div class="sidebar-toggle">
        <i class="icon icon-angle-right"></i>
      </div>
      <div class="cell">
        <details class="detail" open>
          <summary class="detail-title"><?php echo $lang->api->basicInfo;?></summary>
          <div class="detail-content">
            <table class="table table-data">
              <tbody>
              <tr>
                <th class='c-lib'><?php echo $lang->api->lib;?></th>
                <td><?php echo $api->libName;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->module;?></th>
                <td><?php echo $api->moduleName ? $api->moduleName : '/';?></td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->addedDate;?></th>
                <td><?php echo $api->addedDate;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->api->owner;?></th>
                <td><?php echo zget($users, $api->owner, '');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->editedBy;?></th>
                <td><?php echo zget($users, $api->editedBy, '');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->editedDate;?></th>
                <td><?php echo $api->editedDate;?></td>
              </tr>
              </tbody>
            </table>
          </div>
        </details>
      </div>
    </div>
  </div>
</div>
