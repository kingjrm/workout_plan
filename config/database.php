<?php
// Database configuration for JeromeWorkoutPlan Progress Tracking
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "jerome_workout_progress";
    private $conn;

    public function __construct() {
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }

            $this->conn->set_charset("utf8");
        } catch (Exception $e) {
            // If database doesn't exist, create it
            $this->createDatabase();
        }
    }

    private function createDatabase() {
        try {
            $conn = new mysqli($this->host, $this->username, $this->password);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Create database
            $sql = "CREATE DATABASE IF NOT EXISTS " . $this->database;
            if ($conn->query($sql) === TRUE) {
                $conn->select_db($this->database);

                // Run the setup SQL
                $setup_sql = file_get_contents(__DIR__ . '/database_setup.sql');
                $statements = array_filter(array_map('trim', explode(';', $setup_sql)));

                foreach ($statements as $statement) {
                    if (!empty($statement) && !preg_match('/^(CREATE DATABASE|USE)/i', $statement)) {
                        $conn->query($statement);
                    }
                }
            }

            $conn->close();
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            $this->conn->set_charset("utf8");
        } catch (Exception $e) {
            die("Database setup failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);

        if (!empty($params)) {
            $types = '';
            foreach ($params as $param) {
                if (is_int($param)) $types .= 'i';
                elseif (is_float($param)) $types .= 'd';
                else $types .= 's';
            }
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt;
    }

    public function select($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function selectOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function insert($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->insert_id;
    }

    public function update($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->affected_rows;
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

// Initialize database connection
$db = new Database();
?>