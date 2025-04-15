<?php
class Database
{
    protected $connection = null;
    public function __construct()
    {
        try {
            $this->connection = new mysqli(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);
    	
            if ( mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");   
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());   
        }			
    }
    public function select($query = "" , $params = [])
    {
        try {
            $stmt = $this->executeStatement( $query , $params );
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);				
            $stmt->close();
            return $result;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
        return false;
    }

    public function insert($query = "" , $params = [])
    {
        try {
            $stmt = $this->executeStatement( $query , $params ) ;
            $result = $stmt ;
            $stmt->close() ;
            return $result ;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() ) ;
        }
        return false ;
    }

    public function update($query = "", $params = [])
    {
        try {
            $stmt = $this->executeStatement( $query , $params ) ;
            $affectedRows = $stmt->affected_rows;
            $stmt->close() ;
            return $affectedRows ;
        } catch(Exception $e) {
            throw new Exception( $e->getMessage() ) ;
        }   
    }

    public function delete($query = "", $params = []) {
        try {
            $stmt = $this->executeStatement($query, $params);
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            return $affectedRows;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    
    private function executeStatement($query = "" , $params = [])
    {
        try {
            $stmt = $this->connection->prepare( $query );
            if($stmt === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }
            if( $params ) {
                $numParams = count($params) ;
                switch ($numParams) {
                    case (2):
                        $stmt->bind_param($params[0], $params[1]) ;
                        break ;
                    case (3):
                        $stmt->bind_param($params[0], $params[1], $params[2]) ;
                        break ;
                    case (4):
                        $stmt->bind_param($params[0], $params[1], $params[2], $params[3]) ;
                        break ;
                    case (5):
                        $stmt->bind_param($params[0], $params[1], $params[2], $params[3], $params[4]) ;
                        break ;
                    case (6):
                        $stmt->bind_param($params[0], $params[1], $params[2], $params[3], $params[4], $params[5]) ;
                        break ;
                    case (7):
                        $stmt->bind_param($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6]) ;
                        break ;
                }
            }
            $stmt->execute();
            return $stmt;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }	
    }
}
?>
