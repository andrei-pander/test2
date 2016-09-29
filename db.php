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
//    public function getRandRow()
//    {
//        $numRows = $this->execQuery('SELECT count(*) FROM users');
//        $offset = rand(0, array_pop($numRows) - 1);
//        $resArr = $this->execQuery('SELECT * FROM users LIMIT ' . $offset . ', 1');
//        $resArr['status'] = (int)!$resArr['status'];
//        $this->execPreparedQuery('UPDATE users SET status = ? WHERE id = ?', array($resArr['status'], $resArr['id']));
//        array_shift($resArr);
//        return implode(';', $resArr);
//    }
//    public function isExistTable()
//    {
//        return $this->execQuery('SHOW TABLES LIKE \'users\'');
//    }
    private function execQuery($query)
    {
        $sttm = $this->dbh->query($query);
        if (!$sttm) {
            return false;
        }
        return $sttm->fetch(PDO::FETCH_ASSOC);
    }
    private function execPreparedQuery($query, array $queryVars = null)
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
//    public function createTable()
//    {
//        $query = <<<'TEXT'
//CREATE TABLE users (
//  id int(10) unsigned NOT NULL AUTO_INCREMENT,
//  name varchar(128) NOT NULL,
//  status tinyint(3) unsigned NOT NULL,
//  PRIMARY KEY (id)
//) COLLATE 'utf8_general_ci'
//TEXT;
//        $this->execQuery($query);
//    }
    public function importData($rows)
    {
        return $this->execPreparedQuery('INSERT INTO users SET name = ?, status = ?', $rows);
    }
}
