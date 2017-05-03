<?php
class DB {
        private static function connect() {
                $dsn = 'mysql:host=thomsontechcom.ipagemysql.com;dbname=parishcircle';
                $username = 'tom';
                $password = 'enavant@2017';
                $pdo = new PDO($dsn, $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
        }

        public static function query($query, $params = array()) {
                $statement = self::connect()->prepare($query);
                $statement->execute($params);

				$sql_arr = explode(' ', $query);

                if ($sql_arr [0] == 'SELECT') {
                	$data = $statement->fetchAll();
                	return $data;
                }
        }

}


