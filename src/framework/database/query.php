<?php
declare(strict_types=1);

use Framework\Base as Base;
use Framework\ArrayMethods as ArrayMethods;
use Framework\Database\Exception as Exception;

class Query extends Base
{
  /**
  * @readwrite
  */
  protected string $_connector;

  /**
  * @read
  */
  protected string $_from;

  /**
  * @read
  */
  protected array $_fields;

  /**
  * @read
  */
  protected string $_limit;

  /**
  * @read
  */
  protected string $_offset;

  /**
  * @read
  */
  protected string $_order;

  /**
  * @read
  */
  protected string $_direction;

  /**
  * @read
  */
  protected array $_join;

  /**
  * @read
  */
  protected array $_where;

  protected function _getExceptionForImplementation(string $method): object
  {
    return new Exception\Implementation("{$method} method not implemented");
  }

  protected function _quote(?string $value): ?string
  {
    if (is_string($value))
    {
      $escaped = $this->connector->escape($value);
      return "'{$escaped}'";
    }

    if (is_array($value))
    {
      $buffer = array();

      foreach ($value as $i)
      {
        array_push($buffer, $this->_quote($i));
      }

      $buffer = join(", ", $buffer);
      return "({$buffer})";
    }

    if (is_null($value))
    {
      return "NULL";
    }

    if (is_bool($value))
    {
      return (int) $value;
    }

    return $this->connector->escape($value);
  }

  protected function _buildSelect(): string
  {
    $fields = array();
    $where = $order = $limit = $join = "";
    $template = "SELECT %s FROM %s %s %s %s %s";

    foreach ($this->fields as $table => $_fields)
    {
      foreach ($_fields as $field => $alias)
      {
        if (is_string($field))
        {
          $fields[] = "{$field} AS {$alias}";
        }
        else
        {
          $fields[] = $alias;
        }
      }
    }

    $fields = join(", ", $fields);

    $_join = $this->join;

    if (!empty($_join))
    {
      $join = join(" ", $_join);
    }

    $_where = $this->where;

    if (!empty($_where))
    {
      $joined = join(" AND ", $_where);
      $where = "WHERE {$joined}";
    }

    $_order = $this->order;

    if (!empty($_order))
    {
      $_direction = $this->direction;
      $order = "ORDER BY {$_order} {$_direction}";
    }

    $_limit = $this->limit;

    if (!empty($_limit))
    {
      $_offset = $this->offset;

      if ($_offset)
      {
        $limit = "LIMIT {$_limit}, {$_offset}";
      }
      else
      {
        $limit = "LIMIT {$_limit}";
      }
    }

    return sprintf($template, $fields, $this->from, $join, $where, $order, $limit);
  }

  protected function _buildInsert(array $data): string
  {
    $fields = array();
    $values = array();
    $template = "INSERT INTO `%s` (`%s`) VALUES (%s)";

    foreach ($data as $field => $value)
    {
      $fields[] = $field;
      $values[] = $this->_quote($value);
    }

    $fields = join("`, `", $fields);
    $values = join(", ", $values);

    return sprintf($template, $this->from, $fields, $values);
  }

  protected function _buildUpdate(array $data): string
  {
    $parts = array();
    $where = $limit = "";
    $template = "UPDATE %s SET %s %s %s";

    foreach ($data as $field => $value)
    {
      $parts[] = "{$field} = ".$this->_quote($value);
    }

    $parts = join(", ", $parts);

    $_where = $this->where;

    if (!empty($_where))
    {
      $joined = join(", ", $_where);
      $where = "WHERE {$joined}";
    }

    $_limit = $this->limit;

    if (!empty($_limit))
    {
      $_offset = $this->offset;
      $limit = "LIMIT {$_limit} {$_offset}";
    }

    return sprintf($template, $this->from, $parts, $where, $limit);
  }

  protected function _buildDelete(): string
  {
    $where = $limit ="";
    $template = "DELETE FROM %s %s %s";

    $_where = $this->where;

    if (!empty($_where))
    {
      $joined = join(", ", $_where);
      $where = "WHERE {$joined}";
    }

    $_limit = $this->limit;

    if (!empty($_limit))
    {
      $_offset = $this->offset;
      $limit = "LIMIT {$_limit} {$_offset}";
    }

    return sprintf($template, $this->from, $where, $limit);
  }

  public function save(array $data): ?object
  {
    $isInsert = sizeof($this->_where) == 0;

    if ($isInsert)
    {
      $sql = $this->_buildInsert($data);
    }
    else
    {
      $sql = $this->_buildUpdate($data);
    }

    $result = $this->_connector->execute($sql);

    if ($result === false)
    {
      throw new Exception\Sql();
    }

    if ($isInsert)
    {
      return $this->_connector->lastInsertId;
    }

    return 0;
  }

  public function delete(): ?object
  {
    $sql = $this->_buildDelete();
    $result = $this->_connector->execute($sql);

    if ($result === false)
    {
      throw new Exception\Sql();
    }

    return $this->_connector->affectedRows;
  }

  public function from(string $from, array $fields = array("*")): Query
  {
    if (empty($from))
    {
      throw new Exception\Argument("Invalid argument");
    }

    $this->_from = $from;

    if ($fields)
    {
      $this->_fields[$from] = $fields;
    }

    return $this;
  }

  public function join(string $join, string $on, array $fields): Query
  {
    if (empty($join))
    {
      throw new Exception\Argument("Invalid argument");
    }

    if (empty($on))
    {
      throw new Exception\Argument("Invalid argument");
    }

    $this->_fields += array($join => $fields);
    $this->_join[] = "JOIN {$join} ON {$on}";

    return $this;
  }

  public function limit(int $limit, int $page = 1): Query
  {
    if (empty($limit))
    {
      throw new Exception\Argument("Invalid argument");
    }

    $this->_limit = $limit;
    $this->_offset = $limit * ($page - 1);

    return $this;
  }

  public function order(string $order, string $direction = "asc"): Query
  {
    if (empty($order))
    {
      throw new Exception\Argument("Invalid argument");
    }

    $this->_order = $order;
    $this->_direction = $direction;

    return $this;
  }

  public function where(): Query
  {
    $arguments = func_get_args();

    if (sizeof($arguments) < 1)
    {
      throw new Exception\Argument("Invalid argument");
    }

    $arguments[0] = preg_replace("#\?#", "%s", $arguments[0]);

    foreach (array_slice($arguments, 1, null, true) as $i => $parameter)
    {
      $arguments[$i] = $this->_quote($arguments[$i]);
    }

    $this->_where[] = call_user_func_array("sprintf", $arguments);

    return $this;
  }

  public function first(): ?array
  {
    $limit = $this->_limit;
    $offset = $this->_offset;

    $this->limit(1);

    $all = $this->all();
    $first = ArrayMethods::first($all);

    if ($limit)
    {
      $this->_limit = $limit;
    }
    if ($offset)
    {
      $this->_offset = $offset;
    }

    return $first;
  }

  public function count(): int
  {
    $limit = $this->limit;
    $offset = $this->offset;
    $fields = $this->fields;

    $this->_fields = array($this->from => array("COUNT(1)" => "rows"));

    $this->limit(1);
    $row = $this->first();

    $this->_fields = $fields;

    if ($fields)
    {
      $this->_fields = $fields;
    }

    if ($limit)
    {
      $this->_limit = $limit;
    }
    if ($offset)
    {
      $this->_offset = $offset;
    }

    return $row["rows"];
  }
}
