<div class="main-col" data-min-width="400">
  <div id="mainContent" class="main-row in">
    <div class="main-col col-8">
      <div class="cell" id="content">
        <div class="detail no-padding">
          <div class="detail-title no-padding doc-title">
            <div class="http-method label"><?php echo $api->method ?></div>
            <div class="path"><?php echo $api->path; ?></div>
            <div class="title" title="<?php echo $api->title; ?>"><?php echo $api->title; ?></div>
            <div class="info">
              <div class="version">
                <div class='btn-group'>
                  <a href='javascript:;' class='btn btn-link btn-limit text-ellipsis'
                     data-toggle='dropdown' style="max-width: 120px;">
                    #<?php echo $version ? $version : $api->version; ?>
                    <span class="caret"></span>
                  </a>
                  <ul class='dropdown-menu api-version-menu'
                      style='max-height:240px; max-width: 300px; overflow-y:auto'>
                    <?php for($version = $api->version; $version > 0; $version--): ?>
                      <li>
                        <a href='javascript:void(0)'
                           data-url='<?php echo $this->createLink('api', 'index', "libID=0&moduleID=0&apiID=$apiID&version=$version"); ?>'>#<?php echo $version; ?></a>
                      </li>
                    <?php endfor; ?>
                  </ul>
                </div>
              </div>
            </div>
            <div class="actions">
              <?php
              echo html::a("javascript:fullScreen()", '<i class="icon-fullscreen"></i>', '', "title='{$lang->fullscreen}' class='btn btn-link fullscreen-btn'");
              if(common::hasPriv('api', 'edit')) echo html::a(inlink('edit', "apiID=$api->id"), '<i class="icon-edit"></i>', '', "title='{$lang->api->edit}' class='btn btn-link' data-app='{$this->app->tab}'");
              if(common::hasPriv('doc', 'delete'))
              {
                $deleteURL = $this->createLink('api', 'delete', "apiID=$api->id&confirm=yes");
                echo html::a("javascript:ajaxDeleteApi(\"$deleteURL\", confirmDelete)", '<i class="icon-trash"></i>', '', "title='{$lang->api->delete}' class='btn btn-link'");
              }
              ?>
            </div>
          </div>
        </div>
        <div class="table-row">
          <table class="table table-data">
            <tbody>
              <tr>
                <th class='c-lib'><?php echo $lang->api->principal; ?></th>
                <td><?php echo $api->owner; ?></td>
              </tr>
              <tr>
                <th class='c-lib'><?php echo $lang->api->apiDesc; ?></th>
                <td><?php echo $api->desc; ?></td>
              </tr>
            </tbody>
          </table>
          <?php
          $header = array();
          $query  = array();
          $params = array();
          foreach($api->params as $param)
          {
            if($param['scope'] == apiModel::SCOPE_HEADER)
              array_push($header, $param);
            elseif($param['scope'] == apiModel::SCOPE_QUERY)
              array_push($query, $param);
            else
              array_push($params, $param);
          }
          $types = ['header', 'query', 'params']
          ?>
          <?php foreach($types as $type): ?>
            <?php
            if(empty($$type)) continue;
            ?>
            <h3><?php echo $lang->api->$type ?></h3>
            <table class="table table-data paramsTable">
              <thead>
                <tr>
                  <th><?php echo $lang->api->req->name ?></th>
                  <th><?php echo $lang->api->req->type ?></th>
                  <th><?php echo $lang->api->req->required ?></th>
                  <th><?php echo $lang->api->req->desc ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($$type as $param): ?>
                <tr>
                  <td><?php echo $param['field']; ?></td>
                  <td><?php echo $param['paramsType'] ?></td>
                  <td><?php echo $param['required'] ? '是' : '否'; ?></td>
                  <td><?php echo $param['desc']; ?></td>
                <tr>
                  <?php endforeach;
                  ?>
              </tbody>
            </table>
          <?php endforeach; ?>
          <?php if($api->paramsExample): ?>
          <h3><?php echo $lang->api->paramsExample ?></h3>
          <pre><code><?php echo $api->paramsExample ?></code></pre>
          <?php endif;?>
          <?php if($api->response): ?>
          <h3><?php echo $lang->api->response; ?></h3>
          <table class="table">
            <thead>
              <tr>
                <th><?php echo $lang->api->res->name ?></th>
                <th><?php echo $lang->api->res->type ?></th>
                <th><?php echo $lang->api->res->desc ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($api->response as $res): ?>
              <tr>
                <td><?php echo $res['name'] ?></td>
                <td><?php echo $res['type'] ?></td>
                <td><?php echo $res['desc'] ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>
          <?php if($api->responseExample): ?>
            <h3><?php echo $lang->api->responseExample ?></h3>
            <pre><code><?php echo $api->responseExample ?></code></pre>
          <?php endif;?>
        </div>
      </div>
      <!-- 历史记录 -->
      <div class='cell'>
        <?php
        $canBeChanged = common::canBeChanged('api', $api);
        if($canBeChanged) $actionFormLink = $this->createLink('action', 'comment', "objectType=doc&objectID=$api->id");
        ?>
        <?php include '../../common/view/action.html.php'; ?>
      </div>
    </div>
    <div class="side-col col-2" id="sidebar">
      <div class="sidebar-toggle">
        <i class="icon icon-angle-right"></i>
      </div>
      <div class="cell">
        <details class="detail" open>
          <summary class="detail-title"><?php echo $lang->api->basicInfo; ?></summary>
          <div class="detail-content">
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='c-lib'><?php echo $lang->api->lib; ?></th>
                  <td><?php echo $api->libName; ?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->doc->module; ?></th>
                  <td><?php echo $api->moduleName ? $api->moduleName : '/'; ?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->doc->addedDate; ?></th>
                  <td><?php echo $api->addedDate; ?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->doc->editedBy; ?></th>
                  <td><?php echo zget($users, $api->editedBy); ?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->doc->editedDate; ?></th>
                  <td><?php echo $api->editedDate; ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </details>
      </div>
    </div>
  </div>
</div>