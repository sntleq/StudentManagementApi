<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../contracts/IBaseRepository.php';
require_once __DIR__ . '/../models/Student.php';

class StudentRepository implements IBaseRepository {
    protected PDO $databaseContext;
    protected string $table;
    

    public function __construct($databaseContext, $table) {
        $this->databaseContext = $databaseContext;
        $this->table = $table;
    }

    public function GetAll() : array {
        $query = "SELECT * FROM {$this->table}";
        $result = $this->ExecuteSqlQuery($query, []);

        return $this->BuildResultList($result);
    }

    public function GetById(int $id) : ?array {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $result = $this->ExecuteSqlQuery($query, [':id' => $id]);

        return $this->BuildResult($result);
    }

    public function Add($entity) : void {
        $query = "INSERT INTO {$this->table} (name, midterm, finals) VALUES (:name, :midterm, :finals)";
        $params = $this::GetParams($entity, false);

        $this->ExecuteSqlQuery($query, $params);
    }

    public function Update($entity) : void {
        $query = "UPDATE {$this->table} SET name = :name, midterm = :midterm, finals = :finals WHERE id = :id";
        $params = $this::GetParams($entity, true);

        $this->ExecuteSqlQuery($query, $params);
    }

    public function Delete(int $id) : void {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $params = [':id' => $id];

        $this->ExecuteSqlQuery($query, $params);
    }

    private function ExecuteSqlQuery(string $query, array $params) : ?array {
        $statementObject = $this->databaseContext->prepare($query);
        $statementObject->execute($params);

        if (stripos($query, "SELECT") === 0) {
            return $statementObject->fetchAll(PDO::FETCH_ASSOC);
        }

        return null;
    }

    private function BuildResult(?array $result) : ?array {
        if (!$result || empty($result[0])) {
            return null;
        }

        return $result[0];
    }

    private function BuildResultList(array $result) : array {
        $students = [];

        foreach ($result as $row) {
            $students[] = $row;
        }

        return $students;
    }

    private static function GetParams($student, $includeId) : array {
        $params = [
            ':name'    => $student->Name,
            ':midterm' => $student->Midterm,
            ':finals'  => $student->Finals
        ];

        if ($includeId) {
            $params[':id'] = $student->Id;
        }

        return $params;
    }
}
