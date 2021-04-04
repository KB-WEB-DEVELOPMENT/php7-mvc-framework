<?php
declare(strict_types=1);

use Framework\Database\Query as Query;
use Framework\Database\Exception\Sql as Sql;

class Mysql extends Query
{
  public function all(): array
  {
    $sql = $this->_buildSelect();

    $result = $this->connector->execute($sql);

    if ($result === false)

    {

      $error = $this->connector->lastError;
      throw new Sql("There was an error with your SQL query: {$error}");

    }

    $rows = array();

    for ($i = 0; $i < $result->num_rows; $i++)
    {
      $rows[] = $result->fetch_array(MYSQLI_ASSOC);
    }

    return $rows;
  }
}
