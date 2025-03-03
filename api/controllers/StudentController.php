<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../repositories/StudentRepository.php';
require_once __DIR__ . '/../services/StudentService.php';



class StudentController {
    private StudentRepository $studentRepository;
    private StudentService  $studentService;
    private ?PDO $databaseConnection;

    public function __construct() {
        $database = new Database();
        $this->databaseConnection = $database->getConnection();
        $this->studentRepository = new studentRepository($this->databaseConnection, "Student");
        $this->studentService = new StudentService($this->studentRepository);
    }

    public function GetAllStudents() {
        echo json_encode($this->studentService->GetAllStudents());
    }

    public function GetStudentById(int $id) {
        echo json_encode($this->studentService->GetStudentById($id));
    }

    public function AddStudent($entity) {
        try {
            $this->studentService->AddStudent($entity);
            echo "Student Added Successfully";
        } catch (Exception $exception) {
            error_log("Request Handling Error: {$exception->getMessage()}");
        }
    }

    public function UpdateStudent($id, $entity) {
        try {
            $this->studentService->UpdateStudent($id, $entity);
            echo "Student Updated Successfully";
        } catch (Exception $exception) {
            error_log("Request Handling Error: {$exception->getMessage()}");
        }
    }

    public function DeleteStudent(int $id) {
        try {
            $this->studentService->DeleteStudent($id);
            echo "Student Deleted Successfully";
        } catch (Exception $exception) {
            error_log("Request Handling Error: {$exception->getMessage()}");
        }
    }
}