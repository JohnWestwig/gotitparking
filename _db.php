<?php
    $dbconn = new db_connection();
    class db_connection {
        private $dbh;
        public function __construct() {
            
            $dsn = 'mysql:host=jawdb.ccxvwmy9wov7.us-east-1.rds.amazonaws.com;dbname=parku';
            $username = 'JohnWestwig';
            $password = '64Yl7lYTp';

            try {
                $this->dbh = new PDO($dsn, $username, $password);
            }
            catch (PDOException $e) {
                header("Location: login.php");
            }
            
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        
        public function sp_execute($sp, $params) {
            //Prepare call:
            $sp_string = "CALL " . $sp . " (";
            foreach ($params as $param) {
                $sp_string .= "?,";
            }
            $sp_string = rtrim($sp_string, ", ");
            $sp_string .= ")";
                        
            $stmt = $this->dbh->prepare($sp_string);
        
            
            //Execute and return resultant row sets:
            if (count($params) == 0) {
                $stmt->execute();
            } else {
                $stmt->execute($params);
            }
            $result = array();
            do {
                try {
                    $rowset = $stmt->fetchAll();
                    if ($rowset) {
                        array_push($result, $rowset);
                    } else {
                        array_push($result, []);
                    }
                } catch (PDOException $e) {
                    break;
                }
            } while ($stmt->nextRowset());

            return $result;
        }
    }
?>