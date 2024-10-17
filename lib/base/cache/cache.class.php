<?php
/**
 * ZenTaoPHP的cache类。
 * The cache class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * Cache类。
 * Cache class.
 *
 * @package framework
 */
class baseCache
{
    /**
     * 全局对象$app
     * The global app object.
     *
     * @var object
     * @access public
     */
    public $app;

    /**
     * 全局对象$config
     * The global config object.
     *
     * @var object
     * @access public
     */
    public $config;

    /**
     * 缓存类型。
     * The cache driver.
     *
     * @var bool
     * @access public
     */
    public $driver = 'redis';

    /**
     * 正在使用的表。
     * The table of current query.
     *
     * @var string
     * @access public
     */
    public $table;

    /**
     * 查询的字段。
     * The fields will be returned.
     *
     * @var string
     * @access public
     */
    public $fields;

    /**
     * 查询条件。
     * Conditions.
     *
     * @var array
     * @access public
     */
    public $conditions;

    /**
     * 正在组装的查询条件。
     * Condition.
     *
     * @var array
     * @access public
     */
    public $condition;

    /**
     * 构造方法。
     * The construct method.
     *
     * @param  object $app
     * @access public
     * @return void
     */
    public function __construct($app)
    {
        global $config;
        $this->app    = $app;
        $this->config = $config;

        $this->reset();
    }

    /**
     * 设置$table属性。
     * Set the $table property.
     *
     * @param  string $table
     * @access public
     * @return void
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * 设置$fields属性。
     * Set the $fields property.
     *
     * @param  string $fields
     * @access public
     * @return void
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * 设置条件。
     * Set the $conditions property.
     *
     * @param  string $conditions
     * @access public
     * @return void
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
        $this->resetCondition();
    }

    /**
     * 重置组装条件。
     * Reset the $condition property.
     *
     * @access public
     * @return void
     */
    public function resetCondition()
    {
        $this->condition = array('field' => '', 'operator' => '', 'value' => null);
    }

    /**
     * 添加条件。
     * Add the $conditions property.
     *
     * @param  string $condition
     * @access public
     * @return void
     */
    public function addCondition()
    {
        $this->conditions[] = $this->condition;
    }

    /**
     * 重置属性。
     * Reset the vars.
     *
     * @access public
     * @return void
     */
    public function reset()
    {
        $this->setFields('');
        $this->setTable('');
        $this->setConditions([]);
    }

    /**
     * 开始事务。
     * Begin Transaction
     *
     * @access public
     * @return void
     */
    public function begin()
    {
        $this->app->redis->multi();
    }

    /**
     * 提交事务。
     * Commits a transaction.
     *
     * @access public
     * @return void
     */
    public function commit()
    {
        $this->app->redis->exec();
    }

    /**
     * select方法，调用sql::select()。
     * The select method, call sql::select().
     *
     * @param  string $fields
     * @access public
     * @return static|sql|baseDAO the dao object self.
     */
    public function select($fields = '*')
    {
        $this->setFields(explode(',', str_replace(' ', '', $fields)));
        return $this;
    }

    /**
     * 设置要操作的表。
     * Set the from table.
     *
     * @param  string $table
     * @access public
     * @return static|sql the dao object self.
     */
    public function from($table)
    {
        $this->setTable($table);
        return $this;
    }

    /**
     * 创建WHERE部分。
     * Create the where part.
     *
     * @param  string $field the field name
     * @access public
     * @return static the dao object.
     */
    public function where($field)
    {
        $this->resetCondition();
        $this->condition['field'] = $field;
        return $this;
    }

    /**
     * 创建andWHERE部分。
     * Create the andWhere part.
     *
     * @param  string $field the field name
     * @access public
     * @return static the dao object.
     */
    public function andWhere($field)
    {
        $this->resetCondition();
        $this->condition['field'] = $field;
        return $this;
    }

    /**
     * 创建eq部分。
     * Create the eq part.
     *
     * @param  string $value
     * @access public
     * @return static the dao object.
     */
    public function eq($value)
    {
        $this->condition['operator'] = 'eq';
        $this->condition['value']    = $value;
        $this->addCondition();

        return $this;
    }

    /**
     * 创建ne部分。
     * Create the ne part.
     *
     * @param  string $value
     * @access public
     * @return static the dao object.
     */
    public function ne($value)
    {
        $this->condition['operator'] = 'ne';
        $this->condition['value']    = $value;
        $this->addCondition();

        return $this;
    }

    /**
     * 创建in部分。
     * Create the in part.
     *
     * @param  string $value
     * @access public
     * @return static the dao object.
     */
    public function in($value)
    {
        $this->condition['operator'] = 'in';
        $this->condition['value']    = is_string($value) ? explode(',', str_replace(' ', '', $value)) : $value;
        $this->addCondition();

        return $this;
    }

    /**
     * 创建notin部分。
     * Create the in part.
     *
     * @param  string $value
     * @access public
     * @return static the dao object.
     */
    public function notin($value)
    {
        $this->condition['operator'] = 'notin';
        $this->condition['value']    = is_string($value) ? explode(',', str_replace(' ', '', $value)) : $value;
        $this->addCondition();

        return $this;
    }

    //-------------------- Fetch相关方法(Fetch related methods) -------------------//

    /**
     * 判断匹配条件。
     * Check condition is matched.
     *
     * @param  object $object
     * @param  array  $conditions
     * @access public
     * @return bool
     */
    private function isConditionMatched($object, $conditions)
    {
        foreach($conditions as $condition)
        {
            $value = $object->{$condition['field']};
            if($condition['operator'] == 'eq')
            {
                if($condition['value'] !== $value) return false;
            }
            elseif($condition['operator'] == 'eq')
            {
                if($condition['value'] === $value) return false;
            }
            elseif($condition['operator'] == 'in')
            {
                if(!in_array($value, $condition['value'])) return false;
            }
            elseif($condition['operator'] == 'notin')
            {
                if(in_array($value, $condition['value'])) return false;
            }
            else
            {
                return false;
            }
        }

        return true;
    }

    /**
     * 获取一个记录。
     * Fetch one record.
     *
     * @param  string $field        如果已经设置获取的字段，则只返回这个字段的值，否则返回这个记录。
     *                              if the field is set, only return the value of this field, else return this record
     * @access public
     * @return object|mixed
     */
    public function fetch($field = '')
    {
        $cacheTable = $this->config->redis->tables[$this->table]->caches[1]['name'];
        $keyIdList = $this->app->redis->smembers("set:$cacheTable");

        if(empty($keyIdList)) return [];

        $keyPrefix = $this->config->redis->tables[$this->table]->caches[0]['name'];
        $keyList = [];
        foreach($keyIdList as $keyId)
        {
            $keyList[] = "raw:$keyPrefix:$keyId";
        }
        $rawResult = $this->app->redis->mget($keyList);

        if(empty($rawResult)) return '';

        foreach($rawResult as $row)
        {
            $object = json_decode($row);

            if(!$this->isConditionMatched($object, $this->conditions)) continue;

            return $field ? $object->$field : $object;
        }

        return '';
    }

    /**
     * 获取所有记录。
     * Fetch all records.
     *
     * @param  string $keyField     返回以该字段做键的记录
     *                              the key field, thus the return records is keyed by this field
     * @access public
     * @return array the records
     */
    public function fetchAll()
    {
        $cacheTable = $this->config->redis->tables[$this->table]->caches[1]['name'];
        $keyIdList = $this->app->redis->smembers("set:$cacheTable");

        if(empty($keyIdList)) return [];

        $keyPrefix = $this->config->redis->tables[$this->table]->caches[0]['name'];
        $keyList = [];
        foreach($keyIdList as $keyId)
        {
            $keyList[] = "raw:$keyPrefix:$keyId";
        }
        $rawResult = $this->app->redis->mget($keyList);

        if(empty($rawResult)) return [];

        $result = [];
        foreach($rawResult as $row)
        {
            $object = json_decode($row);

            if(!$this->isConditionMatched($object, $this->conditions)) continue;

            $data = new stdclass();
            foreach($this->fields as $field)
            {
                if($field == '*')
                {
                    $data = $object;
                    break;
                }
                $data->$field = $object->$field;
            }

            $result[] = $data;
        }

        return $result;
    }

    /**
     * 获取的记录是以关联数组的形式
     * Fetch array like key=>value.
     *
     * 如果没有设置参数，用首末两键作为参数。
     * If the keyFiled and valueField not set, use the first and last in the record.
     *
     * @param  string $keyField
     * @param  string $valueField
     * @access public
     * @return array
     */
    public function fetchPairs($keyField = '', $valueField = '')
    {
        $pairs = [];

        $rows = $this->fetchAll();
        if(empty($rows)) return [];

        if(empty($keyField))   $keyField = $this->fields[0];
        if(empty($valueField)) $valueField = $this->fields[1];

        foreach($rows as $row)
        {
            $pairs[$row->$keyField] = $row->$valueField;
        }

        return $pairs;
    }
}
