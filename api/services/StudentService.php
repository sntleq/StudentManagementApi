<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../contracts/IBaseRepository.php';

class StudentService {
    public static function ValidateInputs($entity, $checkId) {
        try {
            if (!isset($entity['name']) || empty(trim($entity['name']))) {
                throw new Exception("Student name is required");
            }

            if (!isset($entity['midterm']) || !is_numeric($entity['midterm'])) {
                throw new Exception("Midterm score is required and must be a number");
            }

            if (!isset($entity['finals']) || !is_numeric($entity['finals'])) {
                throw new Exception("Finals score is required and must be a number");
            }

            if ($checkId && (!isset($entity['id']) || !is_numeric($entity['id']))) {
                throw new Exception("Student ID is required and must be a number");
            }
        } catch (Exception $exception) {
            error_log("Input Error: {$exception->getMessage()}");
        }
    }

    public static function ToStudent($entity) : Student {
        $student = new Student();
        $student->Id = $entity['id'] ?? null;
        $student->Name = $entity['name'];
        $student->Midterm = $entity['midterm'];
        $student->Finals = $entity['finals'];
        $student->Grade = 0.4 * $entity['midterm'] + 0.6 * $entity['finals'];
        $student->Status = $student->Grade >= 75 ? 'Passed' : 'Failed';

        return $student;
    }

    public static function GetParams($student, $includeId) : array {
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
