<?php

namespace CustomMVC\Core;

abstract class DBAbstractModel
{

    const SQL_DEFAULT_STATE = '00000';
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
	 * @throws \Exception si falla al crear PDO
	 * Crea el objeto PDO, si este existe, utiliza la misma instancia
	 * para no crear multiples conexiones a la DB
	 */
	private function openConnection()
	{
		try {
			if(!self::$conn) {
				self::$conn = new \PDO(
					'mysql:host=' . self::$dbHost . ';dbname=' . $this->dbName . ';charset=utf8;',
					self::$dbUser,
					self::$dbPass,
					array(\PDO::MYSQL_ATTR_FOUND_ROWS => true)
				);
			}
		} catch (\PDOException $e) {
    		throw new \RuntimeException('Failed to connect to DB.', $e->getCode(), $e);
		}
	}

	/**
	 * @return \PDOStatement $result objeto con los resultados asociados al execute del query
	 * realiza el binding del query (sentencia preparada), ejecuta el query y retorna el Statement
	 */
	private function dbQuery()
	{
        $result = self::$conn->prepare($this->query);
        foreach ($this->bindParams as $key => &$param) {
            $result->bindParam($key, $param);
        }
        $result->execute();

        if ($result->errorCode() !== self::SQL_DEFAULT_STATE) {
            throw new \RuntimeException("You have an error in your SQL syntax");
        }

        return $result;
	}

	/**
	 * permite ejecutar las sentencias UPDATE, INSERT, DELETE
	 */
	protected function executeSingleQuery()
	{
        $this->openConnection();
        $result = $this->dbQuery();
        $this->affectedRows = $result->rowCount();
        $result = null;
	}

	/**
	 * permite ejecutar SELECT, modela el resultado (PDOStatement) en un array $rows
	 */
	protected function getResultsFromQuery()
	{
        $this->openConnection();
        $result = $this->dbQuery();
        $this->rows = $result->fetchAll(\PDO::FETCH_ASSOC);
        $result = null;
	}
}
