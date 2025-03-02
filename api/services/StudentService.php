<?php
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../repositories/StudentRepository.php';

class StudentService {
    private StudentRepository $studentRepository;

    public function __construct($studentRepository) {
        $this->studentRepository = $studentRepository;
    }

    public function GetAllStudents() : array {
        $students = [];

        foreach ($this->studentRepository->GetAll() as $entity) {
            $students[] = $this::ToStudent($entity);
        }

        return $students;
    }

    public function GetStudentById(int $id) : ?Student {
        return $this::ToStudent($this->studentRepository->GetById($id));
    }

    public function AddStudent($entity) {
        if (!self::ValidateInputs($entity)) {
            throw new Exception('Invalid input data');
        }

        $student = $this::ToStudent($entity);
        $this->studentRepository->Add($student);
    }

    public function UpdateStudent($id, $entity) {
        if (!self::ValidateInputs($entity)) {
            throw new Exception('Invalid input data');
        }

        $entity['id'] = $id;

        $student = $this::ToStudent($entity);
        $this->studentRepository->Update($student);
    }

    private static function ValidateInputs($entity) : bool {
        if (!self::ValidateName($entity['name'])) {
            return false;
        }

        if (!self::ValidateMidterm($entity['midterm'])) {
            return false;
        }

        if (!self::ValidateFinals($entity['finals'])) {
            return false;
        }

        return true;
    }

    private static function ValidateName($name) : bool {
        return isset($name) && !empty(trim($name));
    }

    private static function ValidateMidterm($midterm) : bool {
        return isset($midterm) && is_numeric($midterm)
                && 0 <= $midterm && $midterm <= 100;
    }

    private static function ValidateFinals($finals) : bool {
        return isset($finals) && is_numeric($finals)
                && 0 <= $finals && $finals <= 100;
    }

    public static function ToStudent($entity) : ?Student {
        if ($entity == null) {
            return null;
        }

        $student = new Student();
        $student->Id = $entity['id'] ?? null;
        $student->Name = $entity['name'];
        $student->Midterm = $entity['midterm'];
        $student->Finals = $entity['finals'];
        $student->Grade = 0.4 * $entity['midterm'] + 0.6 * $entity['finals'];
        $student->Status = $student->Grade >= 75 ? 'Passed' : 'Failed';

        return $student;
    }
}
