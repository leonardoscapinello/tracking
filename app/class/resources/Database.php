<?php

class Database
{
    private $dbh;
    private $error;
    private $stmt;

    public function __construct()
    {
        $env = new Env();
        $connection_string = "mysql:host=" . $env->get("DB_HOST") . ":" . $env->get("DB_PORT") . ";dbname=" . $env->get("DB_DATABASE") . ";charset=utf8mb4;collation=utf8mb4_unicode_ci";
        try {
            $options = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );
            $this->dbh = new PDO($connection_string, $env->get("DB_USERNAME"), $env->get("DB_PASSWORD"), $options);


        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;

                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;

                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;

                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    public function resultSet()
    {
        $this->execute();
        $result = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->close();
        return $result;

    }

    public function resultSetObject()
    {
        $this->execute();
        $result = $this->stmt->fetch(PDO::FETCH_OBJ);
        $this->close();
        return $result;
    }

    public function execute()
    {
       $this->stmt->execute();
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        $this->execute();
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    /**
     * Transactions allow multiple changes to a database all in one batch.
     */
    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    public function endTransaction()
    {
        return $this->dbh->commit();
    }

    public function cancelTransaction()
    {
        return $this->dbh->rollBack();
    }

    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }

    public function close($close_connection = false)
    {
        if($this->stmt !== null){
            $this->stmt->closeCursor();
        }
        if ($close_connection) $this->dbh = null;
    }

}


?>