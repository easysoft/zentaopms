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
}
