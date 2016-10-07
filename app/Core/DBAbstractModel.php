<?php

namespace CustomMVC\Core;

abstract class DBAbstractModel
{
	/**
	 * @var string
	 */
    private static $dbHost = 'localhost';
	/**
	 * @var string
	 */
    private static $dbUser = 'root';
	/**
	 * @var string
	 */
    private static $dbPass = 'root';
	/**
	 * @var \PDO objeto que permite trabajar con la DB
	 */
    private static $conn;
	/**
	 * @var string
	 */
    protected $dbName = 'test';
	/**
	 * @var string con sentencia SQL
	 */
    protected $query;
	/**
	 * @var array resultado al traer datos de la DB
	 */
    protected $rows = array();
	/**
	 * @var int registros afectados por un query
	 */
    protected $affectedRows;
	/**
	 * @var array con los parámetros para el binding del query
	 */
    protected $bindParams = array();

	/**
	 * @return object|null permite realizar consultas SELECT
	 */
    abstract protected function get();

	/**
	 * permite realizar INSERT
	 */
    abstract protected function set();

	/**
	 * permite realizar UPDATE
	 */
    abstract protected function edit();

	/**
	 * permite realizar DELETE
	 */
    abstract protected function delete();

	/**
	 * @throws \Exception si falla al crear PDO
	 * Crea el objeto PDO, si este existe, utiliza la misma instancia
	 * para no crear multiples conexiones a la DB
	 */
	private function openConnection()
	{
		try
		{
			if(!self::$conn) {
				self::$conn = new \PDO(
					'mysql:host=' . self::$dbHost . ';dbname=' . $this->dbName . ';charset=utf8;',
					self::$dbUser,
					self::$dbPass,
					array(\PDO::MYSQL_ATTR_FOUND_ROWS => true)
				);
			}
		}
		catch (\PDOException $e)
		{
    		throw new \Exception('Failed to connect to the DB');
		}
	}

	/**
	 * @return \PDOStatement $result objeto con los resultados asociados al execute del query
	 * @throws \Exception
	 * realiza el binding del query (sentencia preparada), ejecuta el query y retorna el Statement
	 */
	private function dbQuery()
	{
		try
		{
			$result = self::$conn->prepare($this->query);
			foreach ($this->bindParams as $key => &$param) {
				$result->bindParam($key, $param);
			}
			$result->execute();
			return $result;
		}
		catch (\PDOException $e)
		{
    		throw new \Exception('Error running query on the DB');
		}		
	}

	/**
	 * permite ejecutar las sentencias UPDATE, INSERT, DELETE
	 * @throws \Exception
	 */
	protected function executeSingleQuery()
	{
		try
		{
		    if($_POST) {
		        $this->openConnection();
		        $result = $this->dbQuery();
		        $this->affectedRows = $result->rowCount();
		        $result = null;
		    }
		}
		catch(\Exception $e)
		{
			throw new \Exception('Error running query, '.$e->getMessage());
		}
	}

	/**
	 * permite ejecutar SELECT, modela el resultado (PDOStatement) en un array $rows
	 * @throws \Exception
	 */
	protected function getResultsFromQuery()
	{
		try
		{
	        $this->openConnection();
	        $result = $this->dbQuery();
	        while ($this->rows[] = $result->fetch(\PDO::FETCH_ASSOC));
	        $result= null;
	        array_pop($this->rows);			
		}
		catch(\Exception $e)
		{
			throw new \Exception('Failed to bring results, '.$e->getMessage());
		}
	}
}
