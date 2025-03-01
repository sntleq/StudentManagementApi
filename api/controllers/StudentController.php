<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../repositories/StudentRepository.php';

class StudentController {
    private StudentRepository $studentRepository;
    private ?PDO $databaseConnection;

    public function __construct() {
        $database = new Database();
        $this->databaseConnection = $database->getConnection();
        $this->studentRepository = new studentRepository($this->databaseConnection, "Student");
    }

    public function GetAllStudents() {
        echo json_encode($this->studentRepository->GetAll());
    }

    public function GetStudentById(int $id) {
        echo json_encode($this->studentRepository->GetById($id));
    }

    public function AddStudent($entity) {
        $this->studentRepository->Add($entity);
        echo "Student Added Successfully";
    }

    public function UpdateStudent($id, $entity) {
        $this->studentRepository->Update($id, $entity);
        echo "Student Updated Successfully";
    }

    public function DeleteStudent(int $id) {
        $this->studentRepository->Delete($id);
        echo "Student Deleted Successfully";
    }
}