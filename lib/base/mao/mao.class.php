<?php
/**
 * ZenTaoPHP的mao类。
 * The mao class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * Mao类。
 * Mao class.
 *
 * @package framework
 */
class baseMao
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
     * 全局对象$dao
     * The global dao object.
     *
     * @var object
     * @access protected
     */
    protected $dao;

    /**
     * 全局对象$cache
     * The global cache object.
     *
     * @var object
     * @access public
     */
    public $cache = null;

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
     * @var array
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
     * 是否在判断条件成立。
     * Checking condition.
     *
     * @var bool
     * @access public
     */
    public $isConditionChecking;

    /**
     * 条件是否成立。
     * Condition is true.
     *
     * @var bool
     * @access public
     */
    public $conditionIsTrue;

    /**
     * 待处理的数据。
     * The data to be processed.
     *
     * @var array
     * @access public
     */
    public $data;

    /**
     * 待处理数据的column。
     * The column of data.
     *
     * @var string
     * @access public
     */
    public $dataColumn;

    /**
     * 构造方法。
     * The construct method.
     *
     * @access public
     * @param  object $app
     * @return void
     */
    public function __construct(object $app)
    {
        global $config;
        $this->app    = $app;
        $this->config = $config;
        $this->dao    = $app->dao;
        $this->cache  = $app->cache;

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
    public function setTable(string $table)
    {
        $this->table = $table;
    }

    /**
     * 设置$fields属性。
     * Set the $fields property.
     *
     * @param  array $fields
     * @access public
     * @return void
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * 设置条件。
     * Set the $conditions property.
     *
     * @param  array $conditions
     * @access public
     * @return void
     */
    public function setConditions(array $conditions)
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
        $this->setFields(array());
        $this->setTable('');
        $this->setConditions([]);
    }

    /**
     * select方法，调用sql::select()。
     * The select method, call sql::select().
     *
     * @param  string $fields
     * @access public
     * @return static|sql|baseDAO the dao object self.
     */
    public function select(string $fields = '*')
    {
        $this->conditions = [];

        $fields = explode(',', $fields);

        $alias = [];
        foreach($fields as $field)
        {
            $fieldInfo = explode(' ', trim($field));
            if(count($fieldInfo) == 1)
            {
                $alias[$fieldInfo[0]] = $fieldInfo[0];
            }
            elseif(count($fieldInfo) == 2)
            {
                $alias[$fieldInfo[0]] = $fieldInfo[1];
            }
            else
            {
                $alias[$fieldInfo[0]] = $fieldInfo[2];
            }
        }

        $this->setFields($alias);

        return $this;
    }

    /**
     * 设置要操作的表。
     * Set the from table.
     *
     * @param  string $tableName
     * @access public
     * @return static|sql the dao object self.
     */
    public function from(string $tableName)
    {
        $this->setTable($tableName);
        return $this;
    }

    /**
     * 开始条件判断。
     * Begin condition judge.
     *
     * @param  bool|string $condition
     * @access public
     * @return static|sql the sql object.
     */
    public function beginIF(bool|string $conditionResult)
    {
        $this->isConditionChecking = true;
        $this->conditionIsTrue     = (bool)$conditionResult;
        return $this;
    }

    /**
     * 结束条件判断。
     * End the condition judge.
     *
     * @access public
     * @return static|sql the sql object.
     */
    public function fi()
    {
        $this->isConditionChecking = false;

        if(!$this->conditionIsTrue)
        {
            $this->resetCondition();
            return $this;
        }

        $this->addCondition();
        $this->resetCondition();

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
    public function where(string $field)
    {
        $this->resetCondition();
        $this->condition['field'] = trim($field, '`');
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
    public function andWhere(string $field)
    {
        $this->resetCondition();
        $this->condition['field'] = trim($field, '`');
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

        if(!$this->isConditionChecking)
        {
            $this->addCondition();
            $this->resetCondition();
        }

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

        if(!$this->isConditionChecking)
        {
            $this->addCondition();
            $this->resetCondition();
        }

        return $this;
    }

    /**
     * 创建in部分。
     * Create the in part.
     *
     * @param  string|array $value
     * @access public
     * @return static the dao object.
     */
    public function in(string|array $value)
    {
        $this->condition['operator'] = 'in';
        $this->condition['value']    = is_string($value) ? explode(',', str_replace(' ', '', $value)) : $value;

        if(!$this->isConditionChecking)
        {
            $this->addCondition();
            $this->resetCondition();
        }

        return $this;
    }

    /**
     * 创建notin部分。
     * Create the in part.
     *
     * @param  string|array $value
     * @access public
     * @return static the dao object.
     */
    public function notin(string|array $value)
    {
        $this->condition['operator'] = 'notin';
        $this->condition['value']    = is_string($value) ? explode(',', str_replace(' ', '', $value)) : $value;

        if(!$this->isConditionChecking)
        {
            $this->addCondition();
            $this->resetCondition();
        }

        return $this;
    }

    /**
     * 判断匹配条件。
     * Check condition is matched.
     *
     * @param  object $object
     * @param  array  $conditions
     * @access public
     * @return bool
     */
    private function isConditionMatched(object $object, array $conditions)
    {
        foreach($conditions as $condition)
        {
            $value = $object->{$condition['field']};
            if($condition['operator'] == 'eq')
            {
                if($condition['value'] != $value) return false;
            }
            elseif($condition['operator'] == 'ne')
            {
                if($condition['value'] == $value) return false;
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
     * @param  string $keyField     如果已经设置获取的字段，则只返回这个字段的值，否则返回这个记录。
     *                              if the field is set, only return the value of this field, else return this record
     * @access public
     * @return object|mixed
     */
    public function fetch(string $keyField = '')
    {
        if(empty($this->cache) || empty($this->config->cache->raw[$this->table]) || empty($this->conditions)) return $this->fetchFromDB('fetch', $keyField);

        $rawResult = null;

        /* 如果查询条件中有主键字段，则尝试通过主键字段从缓存中获取数据。If the query condition contains the primary key field, try to get data from the cache by the primary key field. */
        $field = $this->config->cache->raw[$this->table];
        foreach($this->conditions as $condition)
        {
            if($condition['field'] == $field && $condition['operator'] == 'eq')
            {
                $rawResult = $this->cache->fetch($this->table, $condition['value']);
                if($rawResult)
                {
                    $keyField = trim($keyField, '`');
                    return $keyField ? $rawResult->$keyField : $rawResult;
                }
                break;
            }
        }

        return $this->fetchFromDB('fetch', $keyField);
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
    public function fetchAll(string $keyField = ''): array
    {
        if(empty($this->cache) || empty($this->config->cache->raw[$this->table]) || empty($this->conditions)) return $this->fetchFromDB('fetchAll', $keyField, false);

        $rawResult = null;

        /* 如果查询条件匹配到主键字段，则通过主键字段从缓存中获取数据。If the query condition matches the primary key field, get data from the cache by the primary key field. */
        $field = $this->config->cache->raw[$this->table];
        foreach($this->conditions as $condition)
        {
            if($condition['field'] == $field && $condition['operator'] == 'in')
            {
                $value  = $condition['value'];
                if(is_numeric($value)) $value = [$value];
                if(is_string($value))  $value = explode(',', str_replace(' ', '', $value));
                if(is_array($value))
                {
                    $rawResult = $this->cache->fetchAll($this->table, array_filter($value));
                    break;
                }
            }
        }

        /* 如果查询条件没有匹配到主键字段，则从计算结果缓存中获取数据。If the query condition does not match the primary key field, get data from the calculated result cache. */
        //if(is_null($rawResult)) $rawResult = $this->cache->fetchAutoCache($this->table, $this->conditions);

        /* 如果没有缓存，从数据库中获取数据。If there is no cache, get data from the database. */
        if(is_null($rawResult)) return $this->fetchFromDB('fetchAll', $keyField, false);

        if(!$rawResult) return [];

        /* 从缓存中获取到的是原始数据，需要根据查询字段和索引字段进行处理。The data obtained from the cache is raw data, which needs to be processed according to the query fields and index fields. */
        $result = [];
        foreach($rawResult as $index => $row)
        {
            /* 根据主键字段获取数据后，需要逐一匹配查询条件。After getting the data according to the primary key field, you need to match the query conditions one by one. */
            if(!$this->isConditionMatched($row, $this->conditions)) continue;

            $data = new stdclass();
            foreach($this->fields as $field => $alias)
            {
                if($field == '*')
                {
                    $data = $row;
                    break;
                }
                $data->$alias = $row->$field;
            }

            empty($keyField) ? $result[] = $data : $result[$row->$keyField] = $data;
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
    public function fetchPairs(string $keyField = '', string $valueField = '')
    {
        if(empty($this->cache) || empty($this->config->cache->raw[$this->table])) return $this->fetchFromDB('fetchPairs', $keyField, $valueField);

        $rows = $this->fetchAll();
        if(empty($rows)) return [];

        if(empty($keyField))   $keyField   = $this->fields[0];
        if(empty($valueField)) $valueField = $this->fields[1];

        $pairs = [];
        foreach($rows as $row)
        {
            $pairs[$row->$keyField] = $row->$valueField;
        }

        return $pairs;
    }

    /**
     * 从数据库中获取数据。
     * Fetch data from database.
     *
     * @param  string $fetchFunc    fetch|fetchAll
     * @param  string $keyField
     * @param  string|bool $valueField
     * @access private
     * @return mixed
     */
    private function fetchFromDB(string $fetchFunc, string $keyField, string|bool $valueField = '')
    {
        $fields = implode(',', $this->fields);
        $this->dao->select($fields)->from($this->table)->where('1=1');

        foreach($this->conditions as $condition)
        {
            $func = $condition['operator'];
            $this->dao->andWhere($condition['field'])->$func($condition['value']);
        }

        return $this->dao->$fetchFunc($keyField, $valueField);
    }

    /**
     * 把名为 findByXXX 的方法转换为 where 条件。
     * Convert the method findByXXX to where condition.
     *
     * @param  string $method
     * @param  array  $args
     * @access private
     * @return object the mao object.
     */
    private function findBy(string $method, array $args)
    {
        $field = str_replace('findby', '', $method);
        if(count($args) == 1)
        {
            $operator = 'eq';
            $value    = $args[0];
        }
        else
        {
            $operator = $args[0];
            $value    = $args[1];
        }

        $this->setFields(['*']);
        $this->conditions = [['field' => $field, 'operator' => $operator, 'value' => $value]];

        return $this;
    }

    /**
     * 获取缓存数据拼接到已有数据。
     * Append cache fields to data.
     *
     * @param  array  $data
     * @param  string $keyField
     * @access public
     * @return void
     */
    public function into(array $data, $keyField)
    {
        if(empty($data) || empty($keyField)) return $data;

        /* Get data keys as conditions. */
        $keyList = [];
        foreach($data as $index => $row) $keyList[$index] = $row->$keyField;

        if(empty($this->cache))
        {
            /* 如果缓存关闭，从数据库中获取。If the cache is off, get from the database. */
            $primaryKey  = $this->config->cache->raw[$this->table];
            $fields 	 = $primaryKey . ',' . implode(',', array_keys($this->fields));
            $cacheResult = $this->dao->select($fields)->from($this->table)->where($primaryKey)->in(array_unique($keyList))->fetchAll($primaryKey);
        }
        else
        {
            $cacheResult = $this->cache->fetchAll($this->table, array_unique($keyList));
        }

        foreach($data as $index => $row)
        {
            $key = $keyList[$index];
            if(isset($cacheResult[$key]))
            {
                $cacheRow = $cacheResult[$key];
                foreach($this->fields as $field => $alias) $row->$alias = $cacheRow->$field;
            }
            else
            {
                foreach($this->fields as $field => $alias) $row->$alias = '';
            }
        }
    }

    /**
     * 清除缓存。
     * Clear cache.
     *
     * @access public
     * @return void
     */
    public function clearCache()
    {
        if(!empty($this->cache)) $this->cache->clear();
    }

    /**
     * 根据当前缓存键获取缓存。
     * Get cache according to the current cache key.
     *
     * @access public
     * @return mixed
     */
    public function get()
    {
        if(empty($this->cache)) return false;
        return $this->cache->get();
    }

    /**
     * 魔术方法。
     * 1. 转换 findByxxx 为 where 条件。
     * 2. 调用cache对象的方法。
     * Magic method.
     * 1. Convert findByxxx to where condition.
     * 2. Call the cache object method.
     *
     * @param  string $method
     * @param  array  $args
     * @access public
     * @return mixed
     */
    public function __call(string $method, array $args)
    {
        $method = strtolower($method);

        /*
         * 如果是findByxxx，转换为where条件语句。
         * findByxxx, xxx as will be in the where.
         **/
        if(strpos($method, 'findby') !== false) return $this->findBy($method, $args);

        if(empty($this->cache)) return $this;

        if(method_exists($this->cache, $method)) return call_user_func_array([$this->cache, $method], $args);

        $this->app->triggerError("Method $method not found in class baseMao.", __FILE__, __LINE__, $this->config->debug >= 2);
    }
}
