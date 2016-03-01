<?php
namespace CustomMVC\Core;

abstract class DBAbstractModel {

    private static $db_host = 'localhost';
    private static $db_user = 'root';
    private static $db_pass = 'root';
    private static $conn;
    protected $db_name = 'mydb';
    protected $query;
    protected $rows = array();
    protected $affected_rows;
    protected $bind_params = array();
    public $status;

    abstract protected function get();
    abstract protected function set();
    abstract protected function edit();
    abstract protected function delete();

	private function open_connection() {
		try {
			if(!self::$conn) {
				self::$conn = new \PDO(
					'mysql:host=' . self::$db_host . ';dbname=' . $this->db_name . ';charset=utf8;',
					self::$db_user,
					self::$db_pass,
					array(\PDO::MYSQL_ATTR_FOUND_ROWS => true)
				);
			}
		}
		catch (\PDOException $e){
    		throw new \Exception('Error al establecer conexión con la DB');
		}
	}

	private function db_query(){
		try{
			$result = self::$conn->prepare($this->query);
			foreach ($this->bind_params as $key => &$param) {
				$result->bindParam($key, $param);
			}
			$result->execute();
			return $result;
		}
		catch (\PDOException $e){
    		throw new \Exception('Error al ejecutar query en la DB');
		}		
	}

	protected function execute_single_query() {
		try{
		    if($_POST) {
		        $this->open_connection();
		        $result = $this->db_query();
		        $this->affected_rows = $result->rowCount();
		        $result = null;
		    } else {
		        $this->status = 'método no permitido';
		    }
		}
		catch(\Exception $e){
			throw new \Exception('No es posible ejecutar el query, '.$e->getMessage());
		}
	}

	protected function get_results_from_query() {
		try{
	        $this->open_connection();
	        $result = $this->db_query();
	        while ($this->rows[] = $result->fetch(\PDO::FETCH_ASSOC));
	        $result= null;
	        array_pop($this->rows);			
		}
		catch(\Exception $e){
			throw new \Exception('No es posible traer resultados, '.$e->getMessage());
		}
	}
}
