<?php

require('includes/database.php');

//Init Database
$db = new PSBDatabase();
$pdo = $db->getDbh();
//Create tables
$db->createTables();

$view = '';


$action = !empty($_GET['action']) ? $_GET['action'] : 'homepage';
$all_promotions = $db->getPromotions();
$all_teachers = $db->getUsersByType('teacher');
$all_courses = $db->getCourses();

switch ($action) {

    case 'students/list':
        $users = $db->getUsersByType('student');
        $view = 'views/students/list.php';
        break;

    case 'students/add':
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $db->insertUser(
                $_POST['lastname'],
                $_POST['firstname'],
                $_POST['email'],
                $_POST['birthday'],
                $_POST['promotion'] ?? null,
                'student'
            );
            header("Location: ?action=students/list");
        } else {
            $view = 'views/students/add.php';
        }
        break;

    case 'students/edit':
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $db->updateUser($_POST['id'], $_POST['lastname'], $_POST['firstname'], $_POST['email'], $_POST['birthday'], $_POST['promotion'] ?? null);
            header("Location: ?action=students/list");
        } else {
            $userEdited = $db->getUserByID($_GET['id']);
            $view = 'views/students/edit.php';
        }
        break;

    case 'students/delete':
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $db->deleteUser($_POST['student_id']);
            header("Location: ?action=students/list");
        } else {
            $view = 'views/students/list.php';
        }
        break;

    case 'teachers/list':
        $teachers = $db->getUsersByType('teacher');
        $view = 'views/teachers/list.php';
        break;

    case 'teachers/add':
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $db->insertUser(
                $_POST['lastname'],
                $_POST['firstname'],
                $_POST['email'],
                $_POST['birthday'],
                $_POST['promotion'] ?? null,
                'teacher'
            );
            header("Location: ?action=teachers/list");
        } else {
            $view = 'views/teachers/add.php';
        }
        break;

    case 'teachers/edit':
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $db->updateUser($_POST['id'], $_POST['lastname'], $_POST['firstname'], $_POST['email'], $_POST['birthday'], $_POST['promotion'] ?? null);
            header("Location: ?action=teachers/list");
        } else {
            $userEdited = $db->getUserByID($_GET['id']);
            $view = 'views/teachers/edit.php';
        }
        break;

    case 'teachers/delete':
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $db->deleteUser($_POST['teacher_id']);
            header("Location: ?action=teachers/list");
        } else {
            $view = 'views/teachers/list.php';
        }
        break;

    case 'promotions/list':
        $promotions = $db->getPromotions();
        $view = 'views/promotions/list.php';
        break;

    case 'promotions/add':
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            //ajoute la promotion dans la base
            $db->insertPromotion($_POST['name'], $_POST['start'], $_POST['end']);
            //Récupère l'id de la promotion ajoutée
            $id = $pdo->lastInsertId();
            //Insère les cours de la promo
            $db->insertPromotionCourses($id, $_POST['courses']);
            header("Location: ?action=promotions/list");
        } else {
            $view = 'views/promotions/add.php';
        }
        break;

    case 'promotions/show':
        $promotion_show = $db->getPromotionByID($_GET['id']);
        $view = 'views/promotions/show.php';
        break;

    case 'promotions/edit':
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $db->updatePromotion($_POST['id'], $_POST['name'], $_POST['start'], $_POST['end']);
            $db->updatePromotionCourses($_POST['id'], $_POST['courses']);
            header("Location: ?action=promotions/list");
        } else {
            $promotionEdited = $db->getPromotionByID($_GET['id']);
            $promotionCourses = [];
            foreach ($db->getPromotionCourses($_GET['id']) as $c) {
                $promotionCourses[] = $c['id'];
            }
            $view = 'views/promotions/edit.php';
        }
        break;

    case 'promotions/delete':
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $db->deletePromotion($_POST['promotion_id']);
            $db->deletePromotionCourse($_POST['promotion_id']);
            header("Location: ?action=promotions/list");
        } else {
            $view = 'views/promotions/list.php';
        }
        break;


    case 'courses/list':
        $courses = $db->getCourses();
        $view = 'views/courses/list.php';
        break;

    case 'courses/add':
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $db->insertCourse($_POST['name'], $_POST['coefficient'], $_POST['teacher']);
            header("Location: ?action=courses/list");
        } else {
            $view = 'views/courses/add.php';
        }
        break;

    case 'courses/edit':
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $db->updateCourse($_POST['id'], $_POST['name'], $_POST['start'], $_POST['end']);
            header("Location: ?action=courses/list");
        } else {
            $courseEdited = $db->getCourseByID($_GET['id']);
            $view = 'views/courses/edit.php';
        }
        break;

    case 'courses/delete':
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $db->deleteCourse($_POST['course_id']);
            header("Location: ?action=courses/list");
        } else {
            $view = 'views/courses/list.php';
        }
        break;

    default:
        $studentsCount = $db->getStudentsCount();
        $teachersCount = $db->getTeachersCount();
        $coursesCount = $db->getCoursesCount();
        $promotionsCount = $db->getPromotionsCount();
        $view = 'views/home.php';
        break;
}

//Charge les vues communes
include('views/partials/header.php');
include('views/partials/navigation.php');
include('views/partials/sidebar.php');

//Charge la vue dynamique
include($view);

//Charge le footer commun
include('views/partials/footer.php');
