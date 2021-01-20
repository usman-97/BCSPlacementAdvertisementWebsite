<?php

/**
 * Class Match
 */
class Match {

    protected $_dbInstance, $_dbHandle;

    /**
     * Match constructor.
     */
    public function __construct()
    {
        // Creates Instance of Database class
        $this->_dbInstance = Database::getInstance();
        // Establish the connection to database
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
     * Find students with same location and sector as target placement
     * @param $address
     * @param $sector
     * @return array|false
     */
    public function checkLocationSector($address, $sector)
    {
        // SQL query to get users from same location and sector as placement
        $sqlQuery = "SELECT userID FROM users, student WHERE user_type = 'Student' AND postal_address LIKE CONCAT('%', :address, '%') AND users.userID = student.user_id AND sector = :sector";
        $statement = $this->_dbHandle->prepare($sqlQuery); // Prepare PDO statement
        // Assign values to parameters in SQL query
        $statement->bindParam(":address", $address, PDO::PARAM_STR);
        $statement->bindParam(":sector", $sector, PDO::PARAM_STR);
        $statement->execute(); // Execute PDO statement

        $students = []; // list of student with same location and sector as placement
        // If some students are found
        if ($statement->rowCount() > 0)
        {
            // Then fetch records from database
            while ($row = $statement->fetch())
            {
                $students[] = $row['userID'];
            }
            return $students;
        }
        else
        {
            return false;
        }
    }

    /**
     * Find students with same skills and skill level as required
     * skills for placement
     * @param $student
     * @param $placement
     * @return array|false
     */
    public function checkStudentSkills($student, $placement)
    {
        // SQL query to find student skills which match placement
        $sqlQuery = "SELECT skill FROM student_skill, skills, placement_skills WHERE user_id = :student AND student_skill.skill_id = skills.skillID AND skills.skillID = placement_skills.skill_id AND placement_id = :placement AND student_skill.level >= placement_skills.level";
        $statement = $this->_dbHandle->prepare($sqlQuery); // Prepare PDO statement
        // Assign values to parameters in SQL query
        $statement->bindParam(":student",$student, PDO::PARAM_INT);
        $statement->bindParam(":placement",$placement, PDO::PARAM_INT);
        $statement->execute(); // Execute PDO statement

        // List where student matched skills will be stored
        $student = [];
        // If
        if ($statement->rowCount() > 0)
        {
            while ($row = $statement->fetch())
            {
                $student[] = $row['skill'];
            }
            return $student;
        }
        else
        {
            return false;
        }
    }
}