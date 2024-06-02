<?php


class UserRegister
{
    private $conn;
    private $table_name = 'users';

    public $name;
    public $email;
    public $password;
    public $confirm_password;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function setName($name)
    {

        $this->name = $name;
    }
    public function setEmail($email)
    {

        $this->email = $email;
    }
    public function setPassword($password)
    {

        $this->password = $password;
    }
    public function setConfirmPassword($confirm_password)
    {

        $this->confirm_password = $confirm_password;
    }
    // function เช็คว่าพาสตรงกันไหม
    public function validatePassword()
    {
        if ($this->password !== $this->confirm_password) {
            return false; // เช็คว่า password เหมือนกันไหม
        }
        return true; // กรณี password ตรงกัน
    }
    //function เช็คความยาวของ password
    public function checkPasswordLength()
    {       //strlen() เป็นฟังชั่นเช็คความยาวของ string
        if (strlen($this->password) < 6) {
            return false;
        }
        return true;
    }

    public function validateUserInput()
    {
        if (!$this->checkPasswordLength() || !$this->validatePassword() || $this->checkEmail()) {
            return false;
        }
        return true;
    }

    public function createUser()
    {
        //validate  User Input
        if (!$this->validateUserInput()) {
            return false;
        }

        $query = "INSERT INTO {$this->table_name}(name, email, password) VALUES(:name, :email, :password)";
        $stmt = $this->conn->prepare($query);

        //ระบบป้องกันต่างๆ
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        //password_hash() คือฟังชั่นที่จะแปลง password กรอกเข้ามาเช่น 1234 จะกลายเป็น SDAFAVSVEEGRJIULL ตัวอักษรมั่วๆเพื่อที่จะไม่ให้คนที่เข้าถึง db รู้ถึงรหัสได้

        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $hashedPassword);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function checkEmail()
    {
        $query = "SELECT id FROM {$this->table_name} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(":email", $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
