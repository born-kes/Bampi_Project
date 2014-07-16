<?php

/**
 * Class fileMenager zarzadzanie plikami
 */
class fileMenager
{
    /**
     * @class
     * @var true|false $znacznik czy ścieżka zawiera plik
     */
    protected $znacznik;

    /**
     * @var string scieszka katalogow
     */
    protected $where;

    /**
     * @var string nazwa pliku
     */
    protected $whot;

    protected $count;

    protected $path;

    /**
     * @var array lista pobranych danych
     */
    protected $tree = array();



    /**
     * zwraca zebrane dane
     *
     * @param string $val nazwa zmiennej
     *
     * @return array|string zebrane dane
     */
    public function info($val=null)
    {
        if( is_null($val) || !isset($this->$val) )
        {
            return array(
                'where'=>$this->where,
                'whot'=>$this->whot,
                'tree'=>$this->tree,
                'count'=>$this->count,
                'znacznik'=>$this->znacznik

            );
        }
        else if( isset($this->$val) )
        {
            return $this->$val;
        }
        return '';
    }

    /**
     * Filtruje scieszke katalogi i pliki
     *
     * @param string $path scieszka drzewa katalogów
     * @return $this
     */
    public function load($path)
    {
        $this->path = $path;
        $this->catalog();
        $this->file();
        return $this;
    }

    /**
     * include + test istnienia
     * @param string $path scieszka drzewa katalogów
     */
    public function loadInclude($path=null)
    {
        if ( ! is_null($path) )
            $this->load($path);

        if ( isset($this->path) && is_file( $this->path ) )
        {
            include( $this->path );
        } else {
            error("<b>Taka podstrona nie istnieje</b>");
            getMsg();
        }
    }

    /**
     * Izoluje katalog
     *
     * @return string|array
     */
    protected function catalog(){
        preg_match('/^([\w_\-]*\/)+/i', $this->path, $cat);

        $this->where = $cat[0];
        $this->znacznik = false;
      //  $cat=explode('/' , $cat[0] );
      //  $this->tree = array($cat[0]=>array($cat[1]=>array($cat[2])));
        return $this;
    }

    /**
     * Izoluje nazwę pliku
     * @return $this
     */
    protected function file()
    {
        $this->znacznik = false;
        preg_match('/([a-zA-Z0-9_\-]+\.+[\w]{3,4})$/i', $this->path, $file);
        if(!is_null($file[0]))
            $this->znacznik = true;

        $this->whot = $file[0];
        return $this;
    }

    /**
     * Tworzy liste katalogow i plikow
     *
     * @return array liste plikow i katalogów
     */
    public function lista()
	{
        if(! $dir = @opendir($this->where) )
            return false;

        while ($file = readdir($dir)) {
            if ( is_file($this->where.'/'.$file) )
            {
                $array[$file] = 'plik';
            }
            else if( $file[0] != ".")
                $array[$file] = array();
          //  }
        }
        natsort ($array);
        $this->tree[$this->where] =$array;
        return $array;
	}

    /**
     * Pobiera zawartosc pliku
     *
     * @return string|array zawartość pliku
     */
    public function data()
	{
        if(!isset($this->whot)) return null;
        $path = $this->where . $this->whot;

        if ( is_null($this->tree[$path]) && file_exists($path) )
        {
            $this->tree[$path] = file_get_contents($path);

              if( unserialize($this->tree[$path]) )
              {
                $this->tree[$path] = unserialize($this->tree[$path]);
              }
        }
        return $this->tree[$path];
	}

    /**
     * zlicza ilosc plikow|katalogow
     *
     * @param bool $plik wybur pliki lub katalogi
     *
     * @return $this->count ilosc w katalogu
     */
    public function count( $plik = true )
	{ $this->count = 0;

        if(! isset($this->tree[$this->where]) )
        {
            $this->lista();
        }

        if(isset($this->tree[$this->where]))
        {
            foreach($this->tree[$this->where] as $file)
            {

                if( (is_array($file) && !$plik) ||
                   (!is_array($file) &&  $plik) )
                {
                    $this->count++;
                }
            }
        }
        return $this;

	}

    public function rename($new_name)
    {
        if (is_file($this->path) )
        {
            if (file_exists($this->where . $new_name) )
            {
                error('Nie mogę zmienić nazwy. Taki Plik już istnieje.');
            }
            else
            {
                if(rename($this->path , $this->where . $new_name))
                {
                    success('Nazwa została zmieniona.');
                    return true;
                }
                else
                {
                    error('Nazwa nie została zmieniona.');
                }
            }
        }
        return false;
    }

    public function save($save_content)
    {
        $new = file_exists($this->path);
        if (isset($save_content) )
        {
            if( is_array($save_content) )
            {
                $save_content = serialize($save_content);
            }
            if(file_put_contents($this->path, $save_content) )
            {
                if(!$new)
                {
                    chmod($this->path,0777);
                }
                success('Plik został pomyślnie zaktualizowany.');
                return true;
            }
            else
            {
                error('Nie udało się zapisać zmian. Sprawdź czy plik posiada odpowiednie uprawnienia.');
            }
        }
        return false;
    }

    public function delete()
    {
        if( is_file( $this->path ) ) {
            if( unlink( $this->path ) )
                success( 'Wybrana podstrona została usunięta.' );
            else
                error('Nie udało się usunąć podstrony. Sprawdź uprawnienia pliku.');
        }
    }

    public function index($nr)
    {
       $lista = $this->lista();
        return $lista[$nr];
    }

    /**
     * przechwytuje wywolanie nie istniejacej metody i uruchamia jako function
     *
     * @param string $name - nazwa metody
     * @param array $pars argumenty function
     * @desc test
     *
     * @return mixed
     */
    public function __call( $name , $pars ){
        if (function_exists($name))
        {
                call_user_func_array( $name , $pars);
        }
    }


/*
	public function quote($string)
	{
		return $this->pdo->quote($string);
	}

	protected function column_quote($string)
	{
		return '"' . str_replace('.', '"."', preg_replace('/(^#|\(JSON\))/', '', $string)) . '"';
	}

	protected function column_push($columns)
	{
		if ($columns == '*')
		{
			return $columns;
		}

		if (is_string($columns))
		{
			$columns = array($columns);
		}

		$stack = array();

		foreach ($columns as $key => $value)
		{
			preg_match('/([a-zA-Z0-9_\-\.]*)\s*\(([a-zA-Z0-9_\-]*)\)/i', $value, $match);

			if (isset($match[1], $match[2]))
			{
				array_push($stack, $this->column_quote( $match[1] ) . ' AS ' . $this->column_quote( $match[2] ));
			}
			else
			{
				array_push($stack, $this->column_quote( $value ));
			}
		}

		return implode($stack, ',');
	}

	protected function array_quote($array)
	{
		$temp = array();

		foreach ($array as $value)
		{
			$temp[] = is_int($value) ? $value : $this->pdo->quote($value);
		}

		return implode($temp, ',');
	}

	protected function inner_conjunct($data, $conjunctor, $outer_conjunctor)
	{
		$haystack = array();

		foreach ($data as $value)
		{
			$haystack[] = '(' . $this->data_implode($value, $conjunctor) . ')';
		}

		return implode($outer_conjunctor . ' ', $haystack);
	}

	protected function fn_quote($column, $string)
	{
		return (strpos($column, '#') === 0 && preg_match('/^[A-Z0-9\_]*\([^)]*\)$/', $string)) ?

			$string :

			$this->quote($string);
	}

	protected function data_implode($data, $conjunctor, $outer_conjunctor = null)
	{
		$wheres = array();

		foreach ($data as $key => $value)
		{
			$type = gettype($value);

			if (
				preg_match("/^(AND|OR)\s*#?/i", $key, $relation_match) &&
				$type == 'array'
			)
			{
				$wheres[] = 0 !== count(array_diff_key($value, array_keys(array_keys($value)))) ?
					'(' . $this->data_implode($value, ' ' . $relation_match[1]) . ')' :
					'(' . $this->inner_conjunct($value, ' ' . $relation_match[1], $conjunctor) . ')';
			}
			else
			{
				preg_match('/(#?)([\w\.]+)(\[(\>|\>\=|\<|\<\=|\!|\<\>|\>\<)\])?/i', $key, $match);
				$column = $this->column_quote($match[2]);

				if (isset($match[4]))
				{
					if ($match[4] == '')
					{
						$wheres[] = $column . ' ' . $match[4] . '= ' . $this->quote($value);
					}
					elseif ($match[4] == '!')
					{
						switch ($type)
						{
							case 'NULL':
								$wheres[] = $column . ' IS NOT NULL';
								break;

							case 'array':
								$wheres[] = $column . ' NOT IN (' . $this->array_quote($value) . ')';
								break;

							case 'integer':
							case 'double':
								$wheres[] = $column . ' != ' . $value;
								break;

							case 'boolean':
								$wheres[] = $column . ' != ' . ($value ? '1' : '0');
								break;

							case 'string':
								$wheres[] = $column . ' != ' . $this->fn_quote($key, $value);
								break;
						}
					}
					else
					{
						if ($match[4] == '<>' || $match[4] == '><')
						{
							if ($type == 'array')
							{
								if ($match[4] == '><')
								{
									$column .= ' NOT';
								}

								if (is_numeric($value[0]) && is_numeric($value[1]))
								{
									$wheres[] = '(' . $column . ' BETWEEN ' . $value[0] . ' AND ' . $value[1] . ')';
								}
								else
								{
									$wheres[] = '(' . $column . ' BETWEEN ' . $this->quote($value[0]) . ' AND ' . $this->quote($value[1]) . ')';
								}
							}
						}
						else
						{
							if (is_numeric($value))
							{
								$wheres[] = $column . ' ' . $match[4] . ' ' . $value;
							}
							else
							{
								$datetime = strtotime($value);

								if ($datetime)
								{
									$wheres[] = $column . ' ' . $match[4] . ' ' . $this->quote(date('Y-m-d H:i:s', $datetime));
								}
							}
						}
					}
				}
				else
				{
					if (is_int($key))
					{
						$wheres[] = $this->quote($value);
					}
					else
					{
						switch ($type)
						{
							case 'NULL':
								$wheres[] = $column . ' IS NULL';
								break;

							case 'array':
								$wheres[] = $column . ' IN (' . $this->array_quote($value) . ')';
								break;

							case 'integer':
							case 'double':
								$wheres[] = $column . ' = ' . $value;
								break;

							case 'boolean':
								$wheres[] = $column . ' = ' . ($value ? '1' : '0');
								break;

							case 'string':
								$wheres[] = $column . ' = ' . $this->fn_quote($key, $value);
								break;
						}
					}
				}
			}
		}

		return implode($conjunctor . ' ', $wheres);
	}

	protected function where_clause($where)
	{
		$where_clause = '';

		if (is_array($where))
		{
			$where_keys = array_keys($where);
			$where_AND = preg_grep("/^AND\s*#?$/i", $where_keys);
			$where_OR = preg_grep("/^OR\s*#?$/i", $where_keys);

			$single_condition = array_diff_key($where, array_flip(
				explode(' ', 'AND OR GROUP ORDER HAVING LIMIT LIKE MATCH')
			));

			if ($single_condition != array())
			{
				$where_clause = ' WHERE ' . $this->data_implode($single_condition, '');
			}

			if (!empty($where_AND))
			{
				$value = array_values($where_AND);
				$where_clause = ' WHERE ' . $this->data_implode($where[ $value[0] ], ' AND');
			}

			if (!empty($where_OR))
			{
				$value = array_values($where_OR);
				$where_clause = ' WHERE ' . $this->data_implode($where[ $value[0] ], ' OR');
			}

			if (isset($where['LIKE']))
			{
				$like_query = $where['LIKE'];

				if (is_array($like_query))
				{
					$is_OR = isset($like_query['OR']);
					$clause_wrap = array();

					if ($is_OR || isset($like_query['AND']))
					{
						$connector = $is_OR ? 'OR' : 'AND';
						$like_query = $is_OR ? $like_query['OR'] : $like_query['AND'];
					}
					else
					{
						$connector = 'AND';
					}

					foreach ($like_query as $column => $keyword)
					{
						$keyword = is_array($keyword) ? $keyword : array($keyword);

						foreach ($keyword as $key)
						{
							preg_match('/(%?)([a-zA-Z0-9_\-\.]*)(%?)((\[!\])?)/', $column, $column_match);

							if ($column_match[1] == '' && $column_match[3] == '')
							{
								$column_match[1] = '%';
								$column_match[3] = '%';
							}

							$clause_wrap[] =
								$this->column_quote($column_match[2]) .
								($column_match[4] != '' ? ' NOT' : '') . ' LIKE ' .
								$this->quote($column_match[1] . $key . $column_match[3]);
						}
					}

					$where_clause .= ($where_clause != '' ? ' AND ' : ' WHERE ') . '(' . implode($clause_wrap, ' ' . $connector . ' ') . ')';
				}
			}

			if (isset($where['MATCH']))
			{
				$match_query = $where['MATCH'];

				if (is_array($match_query) && isset($match_query['columns'], $match_query['keyword']))
				{
					$where_clause .= ($where_clause != '' ? ' AND ' : ' WHERE ') . ' MATCH ("' . str_replace('.', '"."', implode($match_query['columns'], '", "')) . '") AGAINST (' . $this->quote($match_query['keyword']) . ')';
				}
			}

			if (isset($where['GROUP']))
			{
				$where_clause .= ' GROUP BY ' . $this->column_quote($where['GROUP']);
			}

			if (isset($where['ORDER']))
			{
				$rsort = '/(^[a-zA-Z0-9_\-\.]*)(\s*(DESC|ASC))?/';

				if (is_array($where['ORDER']))
				{
					if (
						isset($where['ORDER'][1]) &&
						is_array($where['ORDER'][1])
					)
					{
						$where_clause .= ' ORDER BY FIELD(' . $this->column_quote($where['ORDER'][0]) . ', ' . $this->array_quote($where['ORDER'][1]) . ')';
					}
					else
					{
						$stack = array();

						foreach ($where['ORDER'] as $column)
						{
							preg_match($rsort, $column, $order_match);

							array_push($stack, '"' . str_replace('.', '"."', $order_match[1]) . '"' . (isset($order_match[3]) ? ' ' . $order_match[3] : ''));
						}

						$where_clause .= ' ORDER BY ' . implode($stack, ',');
					}
				}
				else
				{
					preg_match($rsort, $where['ORDER'], $order_match);

					$where_clause .= ' ORDER BY "' . str_replace('.', '"."', $order_match[1]) . '"' . (isset($order_match[3]) ? ' ' . $order_match[3] : '');
				}

				if (isset($where['HAVING']))
				{
					$where_clause .= ' HAVING ' . $this->data_implode($where['HAVING'], '');
				}
			}

			if (isset($where['LIMIT']))
			{
				if (is_numeric($where['LIMIT']))
				{
					$where_clause .= ' LIMIT ' . $where['LIMIT'];
				}

				if (
					is_array($where['LIMIT']) &&
					is_numeric($where['LIMIT'][0]) &&
					is_numeric($where['LIMIT'][1])
				)
				{
					$where_clause .= ' LIMIT ' . $where['LIMIT'][0] . ',' . $where['LIMIT'][1];
				}
			}
		}
		else
		{
			if ($where != null)
			{
				$where_clause .= ' ' . $where;
			}
		}

		return $where_clause;
	}

	protected function select_context($table, $join, &$columns = null, $where = null, $column_fn = null)
	{
		$table = '"' . $table . '"';
		$join_key = is_array($join) ? array_keys($join) : null;

		if (
			isset($join_key[0]) &&
			strpos($join_key[0], '[') === 0
		)
		{
			$table_join = array();

			$join_array = array(
				'>' => 'LEFT',
				'<' => 'RIGHT',
				'<>' => 'FULL',
				'><' => 'INNER'
			);

			foreach($join as $sub_table => $relation)
			{
				preg_match('/(\[(\<|\>|\>\<|\<\>)\])?([a-zA-Z0-9_\-]*)/', $sub_table, $match);

				if ($match[2] != '' && $match[3] != '')
				{
					if (is_string($relation))
					{
						$relation = 'USING ("' . $relation . '")';
					}

					if (is_array($relation))
					{
						// For ['column1', 'column2']
						if (isset($relation[0]))
						{
							$relation = 'USING ("' . implode($relation, '", "') . '")';
						}
						// For ['column1' => 'column2']
						else
						{
							$relation = 'ON ' . $table . '."' . key($relation) . '" = "' . $match[3] . '"."' . current($relation) . '"';
						}
					}

					$table_join[] = $join_array[ $match[2] ] . ' JOIN "' . $match[3] . '" ' . $relation;
				}
			}

			$table .= ' ' . implode($table_join, ' ');
		}
		else
		{
			if (is_null($columns))
			{
				if (is_null($where))
				{
					if (
						is_array($join) &&
						isset($column_fn)
					)
					{
						$where = $join;
						$columns = null;
					}
					else
					{
						$where = null;
						$columns = $join;
					}
				}
				else
				{
					$where = $join;
					$columns = null;
				}
			}
			else
			{
				$where = $columns;
				$columns = $join;
			}
		}

		if (isset($column_fn))
		{
			if ($column_fn == 1)
			{
				$column = '1';

				if (is_null($where))
				{
					$where = $columns;
				}
				else
				{
					$where = $join;
				}
			}
			else
			{
				if (empty($columns))
				{
					$columns = '*';
					$where = $join;
				}

				$column = $column_fn . '(' . $this->column_push($columns) . ')';
			}
		}
		else
		{
			$column = $this->column_push($columns);
		}

		return 'SELECT ' . $column . ' FROM ' . $table . $this->where_clause($where);
	}

	public function select($table, $join, $columns = null, $where = null)
	{
		$query = $this->query($this->select_context($table, $join, $columns, $where));

		return $query ? $query->fetchAll(
			(is_string($columns) && $columns != '*') ? PDO::FETCH_COLUMN : PDO::FETCH_ASSOC
		) : false;
	}

	public function insert($table, $datas)
	{
		$lastId = array();

		// Check indexed or associative array
		if (!isset($datas[0]))
		{
			$datas = array($datas);
		}

		foreach ($datas as $data)
		{
			$keys = array_keys($data);
			$values = array();
			$columns = array();

			foreach ($data as $key => $value)
			{
				array_push($columns, $this->column_quote($key));

				switch (gettype($value))
				{
					case 'NULL':
						$values[] = 'NULL';
						break;

					case 'array':
						preg_match("/\(JSON\)\s*([\w]+)/i", $key, $column_match);

						if (isset($column_match[0]))
						{
							$values[] = $this->quote(json_encode($value));
						}
						else
						{
							$values[] = $this->quote(serialize($value));
						}
						break;

					case 'boolean':
						$values[] = ($value ? '1' : '0');
						break;

					case 'integer':
					case 'double':
					case 'string':
						$values[] = $this->fn_quote($key, $value);
						break;
				}
			}

			$this->exec('INSERT INTO "' . $table . '" (' . implode(', ', $columns) . ') VALUES (' . implode($values, ', ') . ')');

			$lastId[] = $this->pdo->lastInsertId();
		}

		return count($lastId) > 1 ? $lastId : $lastId[ 0 ];
	}

	public function update($table, $data, $where = null)
	{
		$fields = array();

		foreach ($data as $key => $value)
		{
			preg_match('/([\w]+)(\[(\+|\-|\*|\/)\])?/i', $key, $match);

			if (isset($match[3]))
			{
				if (is_numeric($value))
				{
					$fields[] = $this->column_quote($match[1]) . ' = ' . $this->column_quote($match[1]) . ' ' . $match[3] . ' ' . $value;
				}
			}
			else
			{
				$column = $this->column_quote($key);

				switch (gettype($value))
				{
					case 'NULL':
						$fields[] = $column . ' = NULL';
						break;

					case 'array':
						preg_match("/\(JSON\)\s*([\w]+)/i", $key, $column_match);

						if (isset($column_match[0]))
						{
							$fields[] = $this->column_quote($column_match[1]) . ' = ' . $this->quote(json_encode($value));
						}
						else
						{
							$fields[] = $column . ' = ' . $this->quote(serialize($value));
						}
						break;

					case 'boolean':
						$fields[] = $column . ' = ' . ($value ? '1' : '0');
						break;

					case 'integer':
					case 'double':
					case 'string':
						$fields[] = $column . ' = ' . $this->fn_quote($key, $value);
						break;
				}
			}
		}

		return $this->exec('UPDATE "' . $table . '" SET ' . implode(', ', $fields) . $this->where_clause($where));
	}

	public function delete($table, $where)
	{
		return $this->exec('DELETE FROM "' . $table . '"' . $this->where_clause($where));
	}

	public function replace($table, $columns, $search = null, $replace = null, $where = null)
	{
		if (is_array($columns))
		{
			$replace_query = array();

			foreach ($columns as $column => $replacements)
			{
				foreach ($replacements as $replace_search => $replace_replacement)
				{
					$replace_query[] = $column . ' = REPLACE(' . $this->column_quote($column) . ', ' . $this->quote($replace_search) . ', ' . $this->quote($replace_replacement) . ')';
				}
			}

			$replace_query = implode(', ', $replace_query);
			$where = $search;
		}
		else
		{
			if (is_array($search))
			{
				$replace_query = array();

				foreach ($search as $replace_search => $replace_replacement)
				{
					$replace_query[] = $columns . ' = REPLACE(' . $this->column_quote($columns) . ', ' . $this->quote($replace_search) . ', ' . $this->quote($replace_replacement) . ')';
				}

				$replace_query = implode(', ', $replace_query);
				$where = $replace;
			}
			else
			{
				$replace_query = $columns . ' = REPLACE(' . $this->column_quote($columns) . ', ' . $this->quote($search) . ', ' . $this->quote($replace) . ')';
			}
		}

		return $this->exec('UPDATE "' . $table . '" SET ' . $replace_query . $this->where_clause($where));
	}

	public function get($table, $columns, $where = null)
	{
		if (!isset($where))
		{
			$where = array();
		}

		$where['LIMIT'] = 1;

		$data = $this->select($table, $columns, $where);

		return isset($data[0]) ? $data[0] : false;
	}

	public function has($table, $join, $where = null)
	{
		$column = null;

		return $this->query('SELECT EXISTS(' . $this->select_context($table, $join, $column, $where, 1) . ')')->fetchColumn() === '1';
	}

	public function count($table, $join = null, $column = null, $where = null)
	{
		return 0 + ($this->query($this->select_context($table, $join, $column, $where, 'COUNT'))->fetchColumn());
	}

	public function max($table, $join, $column = null, $where = null)
	{
		$max = $this->query($this->select_context($table, $join, $column, $where, 'MAX'))->fetchColumn();

		return is_numeric($max) ? $max + 0 : $max;
	}

	public function min($table, $join, $column = null, $where = null)
	{
		$min = $this->query($this->select_context($table, $join, $column, $where, 'MIN'))->fetchColumn();

		return is_numeric($min) ? $min + 0 : $min;
	}

	public function avg($table, $join, $column = null, $where = null)
	{
		return 0 + ($this->query($this->select_context($table, $join, $column, $where, 'AVG'))->fetchColumn());
	}

	public function sum($table, $join, $column = null, $where = null)
	{
		return 0 + ($this->query($this->select_context($table, $join, $column, $where, 'SUM'))->fetchColumn());
	}

	public function error()
	{
		return $this->pdo->errorInfo();
	}

	public function last_query()
	{
		return $this->queryString;
	}

	public function info()
	{
		$output = array(
			'server' => 'SERVER_INFO',
			'driver' => 'DRIVER_NAME',
			'client' => 'CLIENT_VERSION',
			'version' => 'SERVER_VERSION',
			'connection' => 'CONNECTION_STATUS'
		);

		foreach ($output as $key => $value)
		{
			$output[ $key ] = $this->pdo->getAttribute(constant('PDO::ATTR_' . $value));
		}

		return $output;
	}*/
}
