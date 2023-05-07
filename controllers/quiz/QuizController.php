<?php
namespace controllers\quiz;

use models\quiz\QuizModel;
use models\roles\Role;
use models\Check;

class QuizController{

    private $check;

    public function __construct()
    {
        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $this->check = new Check($userRole);
    }

    public function index(){
        // $this->check->requirePermission();
        $quizModel = new QuizModel();
        $quizes = $quizModel->readAll();

        include 'app/views/quiz/index.php';
    }

    public function create(){
        // $this->check->requirePermission();
        
        include 'app/views/quiz/create.php';
    }

    public function store(){
        // $this->check->requirePermission();
        
        if(isset($_POST['question']) && isset($_POST['answer_1']) && isset($_POST['answer_2']) && isset($_POST['answer_3']) && isset($_POST['correct_answer'])){
            $data['question'] = trim(htmlspecialchars($_POST['question']));
            $data['answer_1'] = trim(htmlspecialchars($_POST['answer_1']));
            $data['answer_2'] = trim(htmlspecialchars($_POST['answer_2']));
            $data['answer_3'] = trim(htmlspecialchars($_POST['answer_3']));
            $data['correct_answer'] = trim(htmlspecialchars($_POST['correct_answer']));
            $data['explanation'] = trim(htmlspecialchars($_POST['explanation'])) ? trim(htmlspecialchars($_POST['explanation'])) : '';

            $quizModel = new QuizModel();
            $quizModel->createQuiz($data);
        }
        header("Location: /quiz");
    }

    public function edit($params){
        // $this->check->requirePermission();


        $quizModel = new QuizModel();
        $quiz = $quizModel->getQuizById($params['id']);


        include 'app/views/quiz/edit.php';
    }

    public function update($params){
        // $this->check->requirePermission();

        if(isset($_POST['question']) && isset($_POST['answer_1']) && isset($_POST['answer_2']) && isset($_POST['answer_3']) && isset($_POST['correct_answer'])){
            $data['id'] = $_POST['id'];
            $data['question'] = trim(htmlspecialchars($_POST['question']));
            $data['answer_1'] = trim(htmlspecialchars($_POST['answer_1']));
            $data['answer_2'] = trim(htmlspecialchars($_POST['answer_2']));
            $data['answer_3'] = trim(htmlspecialchars($_POST['answer_3']));
            $data['correct_answer'] = trim(htmlspecialchars($_POST['correct_answer']));
            $data['explanation'] = trim(htmlspecialchars($_POST['explanation'])) ? trim(htmlspecialchars($_POST['explanation'])) : '';
           
            $quizModel = new QuizModel();
            $quizModel->updateQuiz($data);
        }
        header("Location: /quiz");
    }

    public function delete($params){
        // $this->check->requirePermission();

        $quizModel = new QuizModel();
        $quizModel->deleteById($params['id']);

        header("Location: /quiz");
    }

    public function search(){
        $inputData = json_decode(file_get_contents("php://input"), true);
        $search = $inputData['question'];
        
        $quizModel = new QuizModel();
        $results = $quizModel->searchQuestions($search);
        
        header("Content-Type: application/json");
        echo json_encode($results);
    }
    
    

}