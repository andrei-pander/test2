<?php
class Database
{
    public $dbh = null;
    public function __construct($dsn, $user, $pass)
    {
        try {
            $this->dbh = new PDO($dsn, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        } catch (PDOException $e) {
            Logger::log($e->getMessage() . '(' . $e->getCode() . ')');
            header($_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        }
    }

    public function execQuery($query)
    {
        $sttm = $this->dbh->query($query);
        if (!$sttm) {
            return false;
        }
        return $sttm->fetch(PDO::FETCH_ASSOC);
    }

    public function execPreparedQuery($query, array $queryVars = null)
    {
        $sttm = $this->dbh->prepare($query);
        if ($queryVars == null) {
            $sttm->execute();
        } elseif (is_array($queryVars[0])) {
            foreach ($queryVars as $row) {
                $sttm->execute($row);
            }
        } else {
            $sttm->execute($queryVars);
        }
        if (!$sttm) {
            return false;
        }
        return $sttm->fetch(PDO::FETCH_ASSOC);
    }

    public function dropTable($table) {
        $table = preg_replace('\S', '', $table);
        echo $table;
    }
}
