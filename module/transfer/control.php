<?php
declare(strict_types=1);
/**
 * The control file of transfer module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     transfer
 * @link        https://www.zentao.net
 */
class transfer extends control
{
    /**
     * 导出数据
     * Export datas.
     *
     * @param  string $model
     * @access public
     * @return void
     */
    public function export(string $model = '')
    {
        if(!empty($_POST))
        {
            $this->transfer->export($model);
            $this->fetch('file', 'export2' . $_POST['fileType'], $_POST);
        }
    }

    /**
     * Export Template.
     *
     * @param  int    $model
     * @param  string $params
     * @access public
     * @return void
     */
    public function exportTemplate($model, $params = '')
    {
        if($_POST)
        {
            $this->loadModel($model);
            if($this->config->edition != 'open')
            {
                $appendFields = $this->dao->select('t2.*')->from(TABLE_WORKFLOWLAYOUT)->alias('t1')
                    ->leftJoin(TABLE_WORKFLOWFIELD)->alias('t2')->on('t1.field=t2.field && t1.module=t2.module')
                    ->where('t1.module')->eq($model)
                    ->andWhere('t1.action')->eq('exporttemplate')
                    ->andWhere('t2.buildin')->eq(0)
                    ->orderBy('t1.order')
                    ->fetchAll();

                foreach($appendFields as $appendField)
                {
                    $this->lang->$model->{$appendField->field} = $appendField->name;
                    $this->config->$model->templateFields .= ',' . $appendField->field;
                }
            }

            if($params)
            {
                /* Split parameters into variables (executionID=1,status=open). */
                $params = explode(',', $params);
                foreach($params as $key => $param)
                {
                    $param = explode('=', $param);
                    $params[$param[0]] = $param[1];
                    unset($params[$key]);
                }
                extract($params);

                /* save params to session. */
                $this->session->set(($model.'TransferParams'), $params);
            }

            $this->loadModel($model);
            $this->config->transfer->sysDataList = $this->transfer->initSysDataFields();

            $fields = $this->config->$model->templateFields;
            if($model == 'task')
            {
                $execution = $this->loadModel('execution')->getByID($executionID);
                if(isset($execution) and $execution->type == 'ops' or in_array($execution->attribute, array('request', 'review'))) $fields = str_replace('story,', '', $fields);
            }

            /* Init config fieldList. */
            $fieldList = $this->transfer->initFieldList($model, $fields);

            $list = $this->transfer->setListValue($model, $fieldList);
            if($list) foreach($list as $listName => $listValue) $this->post->set($listName, $listValue);

            $fields = $this->transfer->getExportDatas($fieldList);

            $this->post->set('fields', $fields['fields']);
            $this->post->set('kind', isset($_POST['kind']) ? $_POST['kind'] : $model);
            $this->post->set('rows', array());
            $this->post->set('extraNum', $this->post->num);
            $this->post->set('fileName', isset($_POST['fileName']) ? $_POST['fileName'] : $model . 'Template');

            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->display();
    }

    /**
     * Import.
     *
     * @param  int    $model
     * @param  string $locate
     * @access public
     * @return void
     */
    public function import($model, $locate = '')
    {
        $locate = $locate ? $locate : $this->session->showImportURL;
        if($_FILES)
        {
            $file = $this->loadModel('file')->getUpload('file');
            if(!empty($file['error'])) return $this->send(array('result' => 'fail', 'message' => $this->lang->file->uploadError[$file['error']]));

            $file      = $file[0];
            $shortName = $this->file->getSaveName($file['pathname']);
            if(empty($shortName)) return $this->send(array('result' => 'fail', 'message' => $this->lang->excel->emptyFileName));

            $extension = $file['extension'];
            $fileName  = $this->file->savePath . $shortName;

            move_uploaded_file($file['tmpname'], $fileName);

            if($extension == 'xlsx' or $extension == 'xls') $this->transfer->cutFile($fileName);

            $phpExcel  = $this->app->loadClass('phpexcel');
            $phpReader = new PHPExcel_Reader_Excel2007();
            if(!$phpReader->canRead($fileName))
            {
                $phpReader = new PHPExcel_Reader_Excel5();
                if(!$phpReader->canRead($fileName)) return $this->send(array('result' => 'fail', 'message' => $this->lang->excel->canNotRead));
            }
            $this->session->set('fileImportFileName', $fileName);
            $this->session->set('fileImportExtension', $extension);

            return $this->send(array('load' => $locate, 'closeModel' => 'true'));
        }

        $this->view->title = $this->lang->transfer->importCase;
        $this->display();
    }

    /**
     * Ajax get Tbody.
     *
     * @param  string $model
     * @param  int    $lastID
     * @param  int    $pagerID
     * @access public
     * @return void
     */
    public function ajaxGetTbody($model = '', $lastID = 0, $pagerID = 1)
    {
        $filter = '';
        if($model == 'task') $filter = 'estimate';
        if($model == 'story' and $this->session->storyType == 'requirement') $this->loadModel('story')->replaceUserRequirementLang();

        $this->loadModel($model);
        $importFields = !empty($_SESSION[$model . 'TemplateFields']) ? $_SESSION[$model . 'TemplateFields'] : $this->config->$model->templateFields;

        if($model == 'testcase' and !empty($_SESSION[$model . 'TemplateFields']) and is_array($importFields)) $this->config->$model->templateFields = implode(',', $importFields);
        $fields       = $this->transfer->initFieldList($model, $importFields, false);
        $formatDatas  = $this->transfer->format($model, $filter);
        $datas        = $this->transfer->getPageDatas($formatDatas, $pagerID);

        if($model == 'story')
        {
            $product = $this->loadModel('product')->getByID($this->session->storyTransferParams['productID']);
            if($product->type == 'normal') unset($fields['branch']);
            if($this->session->storyType == 'requirement') unset($fields['plan']);
        }
        if($model == 'bug')
        {
            $product = $this->loadModel('product')->getByID($this->session->bugTransferParams['productID']);
            if($product->type == 'normal') unset($fields['branch']);
            if($product->shadow and ($this->app->tab == 'execution' or $this->app->tab == 'project')) unset($fields['product']);
        }
        if($model == 'testcase')
        {
            $product = $this->loadModel('product')->getByID($this->session->testcaseTransferParams['productID']);
            if($product->type == 'normal') unset($fields['branch']);
        }
        if($model == 'task') $datas = $this->task->processDatas4Task($datas);
        $html = $this->transfer->buildNextList($datas->datas, $lastID, $fields, $pagerID, $model);
        die($html);
    }

    /**
     * Ajax Get Options.
     *
     * @param  string $model
     * @param  string $field
     * @param  string $value
     * @param  string $index
     * @access public
     * @return void
     */
    public function ajaxGetOptions($model = '', $field = '', $value = '', $index = '')
    {
        $this->loadModel($model);
        $fields = $this->config->$model->templateFields;

        if($this->config->edition != 'open')
        {
            $appendFields = $this->loadModel('workflowaction')->getFields($model, 'showimport', false);

            foreach($appendFields as $appendField) $fields .= ',' . $appendField->field;
        }

        $fieldList = $this->transfer->initFieldList($model, $fields, false);

        if(empty($fieldList[$field]['values'])) $fieldList[$field]['values'] = array('' => '');
        if(!in_array($field, $this->config->transfer->requiredFields)) $fieldList[$field]['values'][''] = '';

        $multiple = $fieldList[$field]['control'] == 'multiple' ? 'multiple' : '';

        $name = $field . "[$index]";
        if($multiple == 'multiple') $name .= "[]";
        return print(html::select($name, $fieldList[$field]['values'], $value, "class='form-control picker-select' data-field='$field' data-index='$index' $multiple"));
    }
}
