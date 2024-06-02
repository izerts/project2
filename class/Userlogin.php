<?php

class UserLogin
{
    private $conn;
    private $table_name = 'users';

    public $email;
    public $password;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function setEmail($email)
    {

        $this->email = $email;
    }
    public function setPassword($password)
    {

        $this->password = $password;
    }

    public function emailNotExists()
    {
        //  method function ที่เอาไว้เช็ค email ว่าห้ามซ้ำ // กำหนด  LIMIT 1 หมายถึง email ที่สมัครมีได้แค่ 1 email ห้ามซ้ำ  
        $query = "SELECT id FROM {$this->table_name} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        // rowCount() ฟังชี่นเอาไว้ใช้นับ
        if ($stmt->rowCount() == 0) {

            return true; // email ose not exists
        } else {
            return false; //email exists
        }
    }

    public function verifyPassword()
    {
        //  method function เอาไว้เช็ค password ว่าตรงกับ confirm password ไหม  
        $query = "SELECT id, password FROM {$this->table_name} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            // fetch(PDO::FETCH_ASSOC) เป็น method ในการดึงข้อมูลของแถวในฐานข้อมูลในรูปแบบ array
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // password ตัวนี้เป็นของในระบบเอามาเก็บในตัวแปร $hashedPassword
            $hashedPassword = $row['password'];
            // $this->password(คือพาสที่ส่งเข้ามา) เอามาเทียบกับ $hashedPassword(พาสในระบบ)
            if (password_verify($this->password, $hashedPassword)) {

                $_SESSION['userid'] = $row['id'];
                header("Location: welcome.php");
            } else {
                return false; // password do not match
            }
        }
        return false; // email not found
    }

    public function userDeta($userid)
    {
        $id = $userid;
        $query = " SELECT * FROM {$this->table_name} WHERE id = :id ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam("id", $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user;
        } else {
            return false;
        }
    }

    public function logout()
    {
        session_start();
        unset($_SESSION['userid']);
        header("Location: signin.php");
    }
}
