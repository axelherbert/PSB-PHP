<?php

class PSBDatabase
{


    private $dbh;

    public function __construct()
    {
        try {
            $this->dbh = new PDO('sqlite:database.sqlite');
            $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die();
        }
    }

    /**
     * Permet de récuprer l'objet PDO permettant de manipuler la base de donnée
     * @return $dbh
     */
    public function getDbh()
    {
        return $this->dbh;
    }

    /**
     * Crée les tables en base de données
     */
    public function createTables()
    {

        $this->dbh->query("CREATE TABLE IF NOT EXISTS Promotion ( 
            id INTEGER  PRIMARY KEY AUTOINCREMENT,
            promotion_name VARCHAR(250) NOT NULL,
            promotion_start DATETIME NOT NULL,
            promotion_end DATETIME NOT NULL
        );");

        $this->dbh->query("CREATE TABLE IF NOT EXISTS Course ( 
            id INTEGER  PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(250) NOT NULL,
            coefficient  INT(1) NOT NULL,
            created_at  DATETIME NOT NULL, 
            teacher_id  INT,
            FOREIGN KEY (teacher_id) REFERENCES Users(teacher_id)
        );");

        $this->dbh->query("CREATE TABLE IF NOT EXISTS PromotionCourses ( 
            course_id INT UNSIGNED NOT NULL,
            promotion_id INT UNSIGNED NOT NULL,
            PRIMARY KEY (course_id, promotion_id),
            CONSTRAINT Constr_PromotionCourses_Promotion_fk
                FOREIGN KEY (promotion_id) REFERENCES Promotion (promotion_id)
                ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT Constr_CourseMembership_Course_fk
                FOREIGN KEY (course_id) REFERENCES Course (course_id)
                ON DELETE CASCADE ON UPDATE CASCADE
        );");

        $this->dbh->query("CREATE TABLE IF NOT EXISTS Users ( 
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email         VARCHAR(250) NOT NULL,
            last_name     VARCHAR(250) NOT NULL,
            first_name    VARCHAR(250) NOT NULL,
            promotion_id  INT,
            user_type     VARCHAR(50) NOT NULL,
            created_at    DATETIME NOT NULL,
            birthday      DATETIME NOT NULL,
            FOREIGN KEY (promotion_id) REFERENCES Promotion(promotion_id)
        );");
    }

    /************** USERS ****************/

    /**
     * Récupère les étudiants
     * @param $type
     * @return array
     */
    public function getUsersByType($type): array
    {
        $stmt = $this->dbh->prepare("
            SELECT Users.id, Users.created_at, Users.last_name, Users.first_name, Users.email, Users.birthday, Promotion.promotion_name 
            FROM Users
            LEFT JOIN Promotion ON Promotion.id = Users.promotion_id 
            WHERE user_type = :type
        ");
        $stmt->execute(['type' => $type]);
        return $stmt->fetchAll();
    }


    /**
     * Ajoute un utilisateur dans la base
     * @param $lastname
     * @param $firstname
     * @param $email
     * @param $birthday
     * @param $promotion_id
     * @param $type
     * @return bool
     */
    public function insertUser($lastname, $firstname, $email, $birthday, $promotion_id, $type): bool
    {
        $stmt = $this->dbh->prepare("
                INSERT INTO Users (email, last_name, first_name, promotion_id, user_type, created_at, birthday) 
                VALUES(:email, :lastname, :firstname, :promotion_id, :userType, :created_at, :birthday)
        ");
        return $stmt->execute([
            'email' => $email,
            'lastname' => $lastname,
            'firstname' => $firstname,
            'userType' => $type,
            'created_at' => date("Y-m-d H:i:s"),
            'birthday' => $birthday,
            'promotion_id' => $promotion_id
        ]);
    }

    /**
     * Modifie un étudiant dans la base
     * @param $id
     * @param $lastname
     * @param $firstname
     * @param $email
     * @param $birthday
     * @param $promotion
     * @return bool
     */
    public function updateUser($id, $lastname, $firstname, $email, $birthday, $promotion): bool
    {
        $stmt = $this->dbh->prepare("
                UPDATE Users 
                SET last_name = :lastname, first_name = :firstname, email = :email, birthday = :birthday, promotion_id = :promotion_id
                WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'email' => $email,
            'lastname' => $lastname,
            'firstname' => $firstname,
            'birthday' => $birthday,
            'promotion_id' => $promotion,
        ]);
    }

    /**
     * Modifie une promotion dans la base
     * @param $id
     * @param $name
     * @param $start
     * @param $end
     * @return bool
     */
    public function updatePromotion($id, $name, $start, $end): bool
    {
        $stmt = $this->dbh->prepare("
                UPDATE Promotion 
                SET promotion_name = :name, promotion_start = :start, promotion_end = :end
                WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'start' => $start,
            'end' => $end
        ]);
    }



    /**
     * Récupère un utilisateur par ID
     * @param $id
     * @return mixed
     */
    public function getUserByID($id)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM Users WHERE id = :id");
        $stmt->execute(['id' => (int)$id]);
        return $stmt->fetchAll()[0];
    }

    /**
     * Récupère une promotion par ID
     * @param $id
     * @return mixed
     */
    public function getPromotionByID($id)
    {
        $stmt = $this->dbh->prepare("
            SELECT * FROM Promotion WHERE id = :id
         ");
        $stmt->execute(['id' => (int)$id]);
        return $stmt->fetchAll()[0];
    }

    /**
     * Récupère les matières d'une promo
     * @param $id
     * @return mixed
     */
    public function getPromotionCourses($id)
    {
        $stmt = $this->dbh->prepare("
            SELECT Course.id
            FROM PromotionCourses 
                JOIN Promotion ON PromotionCourses.promotion_id = Promotion.id
                join Course ON PromotionCourses.course_id = Course.id
            WHERE Promotion.id = :id
         ");
        $stmt->execute(['id' => (int)$id]);
        return $stmt->fetchAll();
    }

    /**
     * Supprime un user
     * @param $id
     * @return bool
     */
    public function deleteUser($id): bool
    {
        $stmt = $this->dbh->prepare("DELETE FROM Users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /************** PROMOTIONS ****************/
    /**
     * Récupère les promotions
     * @return array
     */
    public function getPromotions(): array
    {
        $stmt = $this->dbh->prepare("
            SELECT 
                   count(CASE WHEN Users.user_type = 'student' THEN 1 ELSE NULL END) as nbStudents, 
                   count(CASE WHEN Users.user_type = 'teacher' THEN 1 ELSE NULL END) as nbTeachers,
                   count(PromotionCourses.course_id) as nbCourses,
                   Promotion.id, 
                   Promotion.promotion_name, 
                   Promotion.promotion_start, 
                   Promotion.promotion_end 
            FROM Promotion  
            LEFT JOIN Users ON Users.promotion_id = Promotion.id
            LEFT JOIN PromotionCourses ON PromotionCourses.promotion_id = Promotion.id
            GROUP BY Promotion.id

        ");
        $stmt->execute([]);
        return $stmt->fetchAll();
    }

    /**
     * Ajoute une promotion dans la base
     * @param $name
     * @param $start
     * @param $end
     * @return bool
     */
    public function insertPromotion($name, $start, $end): bool
    {
        $stmt = $this->dbh->prepare("
                INSERT INTO Promotion (promotion_name, promotion_start, promotion_end) 
                VALUES(:name, :start, :end)
        ");
        return $stmt->execute([
            'name' => $name,
            'start' => $start,
            'end' => $end
        ]);
    }

    /**
     * Ajoute des matières à une promotion dans la base
     * @param $promotionId
     * @param $courses
     * @return bool
     */
    public function insertPromotionCourses($promotionId, $courses): bool
    {
        foreach ($courses as $course) {
            $stmt = $this->dbh->prepare("
                INSERT INTO PromotionCourses (course_id, promotion_id) 
                VALUES(:course_id, :promotion_id)
            ");
            $stmt->execute([
                'course_id' => $course,
                'promotion_id' => $promotionId
            ]);
        }
        return true;
    }

    /**
     * Modifie des matières à une promotion dans la base
     * @param $promotionId
     * @param $courses
     * @return bool
     */
    public function updatePromotionCourses($promotionId, $courses): bool
    {
        $stmt = $this->dbh->prepare("
                DELETE FROM PromotionCourses
                WHERE promotion_id = :promotion_id
            ");
        $stmt->execute([
            'promotion_id' => $promotionId
        ]);
        foreach ($courses as $course) {
            $stmt = $this->dbh->prepare("
                INSERT INTO PromotionCourses (course_id, promotion_id) 
                VALUES(:course_id, :promotion_id)
            ");
            $stmt->execute([
                'course_id' => $course,
                'promotion_id' => $promotionId
            ]);
        }
        return true;
    }

    /**
     * Supprime une promotion
     * @param $id
     * @return bool
     */
    public function deletePromotion($id): bool
    {
        $stmt = $this->dbh->prepare("DELETE FROM Promotion WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Supprime les matières d'une promo
     * @param $id
     * @return bool
     */
    public function deletePromotionCourse($id): bool
    {
        $stmt = $this->dbh->prepare("DELETE FROM PromotionCourses WHERE promotion_id = :id");
        return $stmt->execute(['id' => $id]);
    }


    /************** Matières ****************/

    /**
     * Récupère les matières
     * @return array
     */
    public function getCourses(): array
    {
        $stmt = $this->dbh->prepare("
            SELECT Course.id, Course.name, Course.coefficient, Course.created_at, Users.email as teacher_email 
            FROM Course
            LEFT JOIN Users ON Course.teacher_id = Users.id
            AND Users.user_type = :type
        ");
        $stmt->execute(['type' => 'teacher']);
        return $stmt->fetchAll();
    }

    /**
     * Ajoute une matière dans la base
     * @param $name
     * @param $coefficient
     * @param $teacher_id
     * @return bool
     */
    public function insertCourse($name, $coefficient, $teacher_id): bool
    {
        $stmt = $this->dbh->prepare("
                INSERT INTO Course (name, coefficient, created_at, teacher_id) 
                VALUES(:name, :coefficient, :created_at, :teacher_id)
        ");
        return $stmt->execute([
            'name' => $name,
            'coefficient' => $coefficient,
            'teacher_id' => $teacher_id,
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }

    /**
     * Modified un cours dans la base
     * @param $id
     * @param $name
     * @param $coefficient
     * @param $teacher
     * @return bool
     */
    public function updateCourse($id, $name, $coefficient, $teacher): bool
    {
        $stmt = $this->dbh->prepare("
                UPDATE Course 
                SET name = :name, coefficient = :coefficient, teacher_id = :teacher_id
                WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'coefficient' => $coefficient,
            'teacher_id' => $teacher
        ]);
    }

    /**
     * Récupère une matière par ID
     * @param $id
     * @return mixed
     */
    public function getCourseByID($id)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM Course WHERE id = :id");
        $stmt->execute(['id' => (int)$id]);
        return $stmt->fetchAll()[0];
    }

    /**
     * Supprime une matière
     * @param $id
     * @return bool
     */
    public function deleteCourse($id): bool
    {
        $stmt = $this->dbh->prepare("DELETE FROM Course WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }




    /************** COUNT ****************/


    /**
     * Retourne le nombre d'étudiants
     * @return array
     */
    public function getStudentsCount()
    {
        $stmt = $this->dbh->prepare("SELECT count(*) FROM Users WHERE user_type = :type");
        $stmt->execute(['type' => 'student']);
        return $stmt->fetchAll()[0]['count(*)'];
    }

    /**
     * Retourne le nombre de professeurs
     * @return array
     */
    public function getTeachersCount()
    {
        $stmt = $this->dbh->prepare("SELECT count(*) FROM Users WHERE user_type = :type");
        $stmt->execute(['type' => 'teacher']);
        return $stmt->fetchAll()[0]['count(*)'];
    }

    /**
     * Retourne le nombre de matières
     * @return array
     */
    public function getCoursesCount()
    {
        $stmt = $this->dbh->prepare("SELECT count(*) FROM Course");
        $stmt->execute();
        return $stmt->fetchAll()[0]['count(*)'];
    }

    /**
     * Retourne le nombre de promotions
     * @return array
     */
    public function getPromotionsCount()
    {
        $stmt = $this->dbh->prepare("SELECT count(*) FROM Promotion");
        $stmt->execute();
        return $stmt->fetchAll()[0]['count(*)'];
    }
}
