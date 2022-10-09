<?php
/**
 * Dtable class.
 *
 * @copyright Copyright 2009-2022 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author    guanxiying <guanxiying@easycorp.ltd>
 * @package
 * @license   LGPL
 * @version   $Id$
 * @Link      https://www.zentao.net
 */
class dtable
{
    /**
     * Options of dtable.
     */
    public $options;

    /**
     * cols of dtable options.
     */
    public $cols;

    /**
     * data of dtable options.
     */
    public $data;

    /**
     * Init cols.
     *
     * @param  array    $cols
     * @access public
     * @return object $this
     */
    public function colInit($cols)
    {
        $this->cols = $cols;
        return $this;
    }

    /**
     * colSet
     *
     * @param  string    $colName
     * @param  object    $col
     * @access public
     * @return object $this
     */
    public function colSet($colName, $col)
    {
        $this->cols->$colName = $col;
        return $this;
    }

    /**
     * Delete one col.
     *
     * @param  string    $colName
     * @access public
     * @return object
     */
    public function colDelete($colName)
    {
        unset($this->cols->$colName);
        return $this;
    }

    /**
     * set col attr.
     *
     * @param  string    $colName
     * @param  string    $property
     * @param  mixed     $value
     * @access public
     * @return object
     */
    public function colAttr($colName, $property, $value)
    {
        if(!isset($this->cols->$colName)) $this->cols->$colName = new stdClass;
        $this->cols->{$colName}->{$property} = $value;
        return $this;
    }

    /**
     * dataInit
     *
     * @param  array    $data
     * @access public
     * @return object
     */
    public function dataInit($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * dataAappend
     *
     * @param  object    $row
     * @access public
     * @return object
     */
    public function dataAappend($row)
    {
        $this->data[] = $row;
        return $this;
    }

    /**
     * dataSet
     *
     * @param  string    $index
     * @param  object    $data
     * @access public
     * @return object
     */
    public function dataSet($index, $data)
    {
        $this->data[$index] = $data;
        return $this;
    }

    /**
     * dataBatchSet
     *
     * @param  mixed    $scope [1,2,3,4] | all
     * @param  object    $data
     * @access public
     * @return object
     */
    public function dataBatchSet($scope, $data)
    {
        if($scope == 'all') $scope = array_keys($this->data);

        foreach($scope as $index) $this->data[$index] = $data;

        return $this;
    }

    /**
     * Get dtable options.
     *
     * @access public
     * @return object
     */
    public function getOptions($extra)
    {
        $this->options = new stdClass;
        $this->options->cols = $this->cols;
        $this->options->data = $this->data;
        return $this->options;
    }

    /**
     * Show dtable codes.
     *
     * @access public
     * @return string
     */
    public function show()
    {

    }
}
