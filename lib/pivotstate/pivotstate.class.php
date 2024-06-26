<?php
class pivotState
{
    /**
     * Pivot state ID.
     *
     * @var int
     * @access public
     */
    public $id;

    /**
     * Pivot state dimension.
     *
     * @var string
     * @access public
     */
    public $dimension;

    /**
     * Pivot state group.
     *
     * @var string
     * @access public
     */
    public $group;

    /**
     * Pivot state code.
     *
     * @var string
     * @access public
     */
    public $code;

    /**
     * Pivot state driver.
     *
     * @var string
     * @access public
     */
    public $driver;

    /**
     * Pivot state name.
     *
     * @var string
     * @access public
     */
    public $name;

    /**
     * Pivot state description.
     *
     * @var string
     * @access public
     */
    public $desc;

    /**
     * Pivot state SQL.
     *
     * @var string
     * @access public
     */
    public $sql;

    /**
     * Pivot state fields.
     *
     * @var array
     * @access public
     */
    public $fields;

    /**
     * Pivot state fieldSettings.
     *
     * @var array
     * @access public
     */
    public $fieldSettings;

    /**
     * Pivot state languages.
     *
     * @var array
     * @access public
     */
    public $langs;

    /**
     * Pivot state variables.
     *
     * @var array
     * @access public
     */
    public $vars;

    /**
     * Pivot state objects.
     *
     * @var array
     * @access public
     */
    public $objects;

    /**
     * Pivot state settings.
     *
     * @var array
     * @access public
     */
    public $settings;

    /**
     * Pivot state filters.
     *
     * @var array
     * @access public
     */
    public $filters;

    /**
     * Pivot state step.
     *
     * @var int
     * @access public
     */
    public $step;

    /**
     * Pivot state stage.
     *
     * @var string
     * @access public
     */
    public $stage;

    /**
     * Pivot stage action.
     *
     * @var string
     * @access public
     */
    public $action = 'design';

    /**
     * error
     *
     * @var bool
     * @access public
     */
    public $error = false;

    /**
     * errorMsg
     *
     * @var string
     * @access public
     */
    public $errorMsg = '';

    /**
     * queryCols
     *
     * @var array
     * @access public
     */
    public $queryCols = array();

    /**
     * queryData
     *
     * @var array
     * @access public
     */
    public $queryData = array();

    /**
     * pivotCols
     *
     * @var array
     * @access public
     */
    public $pivotCols = array();

    /**
     * pivotData
     *
     * @var array
     * @access public
     */
    public $pivotData = array();

    /**
     * pivotCellSpan
     *
     * @var array
     * @access public
     */
    public $pivotCellSpan = array();

    /**
     * pager
     *
     * @var int
     * @access public
     */
    public $pager;

    public function __construct($pivot)
    {
        $this->id        = $pivot->id;
        $this->dimension = $pivot->dimension;
        $this->group     = $pivot->group;
        $this->code      = $pivot->code;
        $this->driver    = $pivot->driver;
        $this->name      = $pivot->name;
        $this->desc      = $pivot->desc;
        $this->sql       = $pivot->sql;
        $this->step      = 1;
        $this->stage     = $pivot->stage;

        $this->fields    = $this->json2Array($pivot->fieldSettings);
        $this->langs     = $this->json2Array($pivot->langs);
        $this->vars      = $this->json2Array($pivot->vars);
        $this->objects   = $this->json2Array($pivot->objects);
        $this->settings  = $this->json2Array($pivot->settings);
        $this->filters   = $this->json2Array($pivot->filters);

        $this->fieldSettings = array_merge_recursive($this->fields, $this->langs);
        $this->setPager();
    }

    /**
     * Update from $_POST.
     *
     * @param  array    $post
     * @access public
     * @return void
     */
    public function updateFromPost($post)
    {
        if(!isset($post['pivotState'])) return;
        $json = $post['pivotState'];
        $array = json_decode($json, true);

        extract($array);

        $this->id        = $id;
        $this->dimension = $dimension;
        $this->group     = $group;
        $this->code      = $code;
        $this->driver    = $driver;
        $this->name      = $name;
        $this->desc      = $desc;
        $this->sql       = $sql;
        $this->step      = $step;
        $this->stage     = $stage;

        $this->fields    = $fields;
        $this->langs     = $langs;
        $this->vars      = $vars;
        $this->objects   = $objects;
        $this->settings  = $settings;
        $this->filters   = $filters;

        $this->action        = $action;
        $this->queryCols     = $queryCols;
        $this->queryData     = $queryData;
        $this->pivotCols     = $pivotCols;
        $this->pivotData     = $pivotData;
        $this->pivotCellSpan = $pivotCellSpan;

        $this->fieldSettings = $fieldSettings;
        $this->setPager($pager['total'], $pager['recPerPage'], $pager['pageID']);
    }

    /**
     * Clear properies before query sql.
     *
     * @access public
     * @return void
     */
    public function beforeQuerySql()
    {
        $this->error         = false;
        $this->errorMsg      = '';
        $this->queryCols     = array();
        $this->queryData     = array();
        $this->pivotCols     = array();
        $this->pivotData     = array();
        $this->pivotCellSpan = array();
        $this->setPager();
    }

    /**
     * Set fieldSettings with merge.
     *
     * @param  array    $settings
     * @access public
     * @return void
     */
    public function setFieldSettings($settings)
    {
        $settings      = (array)$settings;
        $fieldSettings = array();

        foreach($settings as $field => $setting)
        {
            if(isset($this->fieldSettings[$field]))
            {
                $fieldSettings[$field] = array_merge($this->fieldSettings[$field], array('name' => $field));
            }
        }

        $this->fieldSettings = $fieldSettings;
    }

    /**
     * Build cols for query sql with lang.
     *
     * @param  string    $lang
     * @access public
     * @return object
     */
    public function buildQuerySqlCols($lang)
    {
        $cols = array();
        foreach($this->fieldSettings as $field => $settings)
        {
            $settings = (array)$settings;
            $title    = isset($settings[$lang]) ? $settings[$lang] : $field;
            $type     = $settings['type'];

            $cols[] = array('name' => $field, 'title' => $title, 'sortType' => false);
        }

        $this->queryCols = $cols;
        return $this;
    }

    /**
     * Set action.
     *
     * @param  string    $action
     * @access public
     * @return void
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Set step.
     *
     * @param  int    $step
     * @access public
     * @return void
     */
    public function setStep($step)
    {
        $this->step = $step;
    }

    /**
     * Judge is publish action.
     *
     * @access public
     * @return bool
     */
    public function isPublish()
    {
        return $this->action == 'publish';
    }

    /**
     * Judge is design action.
     *
     * @access public
     * @return bool
     */
    public function isDesign()
    {
        return $this->action == 'design';
    }

    /**
     * Judge is first design action.
     *
     * @access public
     * @return bool
     */
    public function isFirstDesign()
    {
        return $this->isDesign() && empty($this->sql);
    }

    /**
     * Judge is error.
     *
     * @access public
     * @return bool
     */
    public function isError()
    {
        return $this->error;
    }

    /**
     * Get error message.
     *
     * @access public
     * @return string
     */
    public function getError()
    {
        return $this->errorMsg;
    }

    /**
     * Set error.
     *
     * @param  string    $msg
     * @access public
     * @return object
     */
    public function setError($msg)
    {
        $this->errror   = true;
        $this->errorMsg = $msg;

        return $this;
    }

    /**
     * Set pager
     *
     * @param  int    $total
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function setPager($total = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->pager = new stdclass();
        $this->pager->total      = $total;
        $this->pager->recPerPage = $recPerPage;
        $this->pager->pageID     = $pageID;
    }
}
