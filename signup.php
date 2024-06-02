<?php include_once('header.php'); ?>

<?php include_once('nav.php'); ?>





<div class="container">
    <h3 class="my-3">Register Page</h3>

    <?php



    //เป็นการเรียกใช้ขอการเข้าถึงไฟล์ดังกล่าว
    include_once('config/Database.php');
    include_once('class/UserRegister.php');
    include_once('class/utils.php');

    //มีการสร้างตัวแปร obj instanct ขึ้นมาก็คือตัว $connectDB เพื่อให้สามารถเข้าถึง attibute,function ต่างๆในคลาส Databaseได้
    $connectDB = new Database();
    // สร้างตัวแปร $db เพื่อเก็บค่า $connectDB ที่ไปขอเรียกใช้การเข้าถึงฟังชั่น getConnection() หรือก็คือฟังชั่น การเชื่อมต่อฐานข้อมูล
    $db = $connectDB->getConnection();

    //มีการสร้างตัวแปร obj instanct ขึ้นมาก็คือตัว $user  เพื่อให้สามารถเข้าถึง attibute,function ต่างๆในคลาส UserRegister ด้
    $user = new UserRegister($db);
    $bs = new bootstrap();




    // มีการเช็คว่าถ้าเกิดมีการกด submit form มา เราจะทำการให้ตัวแปร $user ไปเข้าถึงฟังชั่น set ต่างๆมา
    // โครงสร้าง  isset()คือฟังชั่น ตรวจสอบว่าตัวแปรนั้นมีการกำหนดค่าไว้หรือไม่ ส่วน $_POST อันนี้มาจาก method="POST" ตามด้วย 'signup' มาจากปุ่ม submit เรา
    if (isset($_POST['signup'])) {
        $user->setName($_POST['name']); // ค่าใน array นี้คือมากจาก input name=" "
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);
        $user->setConfirmPassword($_POST['confirm_password']);




        if (!$user->validatePassword()) {
            $bs->displayAlert("Password do not match", "danger");
            // echo "<div class='alert alert-danger' role='alert'>Password do not match </div>";
        }
        if (!$user->checkPasswordLength()) {
            $bs->displayAlert("Password must be at least 6 charactor long", "danger");
            // echo "<div class='alert alert-danger' role='alert'>Password must be at least 6 charactor long </div>";
        }
        if ($user->checkEmail()) {
            $bs->displayAlert("This email is already existe ", "danger");
            //  echo "<div class='alert alert-danger' role='alert'>This email is already existe </div>";
        }
        if ($user->createUser()) {
            $bs->displayAlert("User Creat Successfully.", "success");
            //  echo "<div class='alert alert-success' role='alert'> User Creat Successfully. </div>";
        } else {
            $bs->displayAlert("Fail to Create User. ", "danger");
            // echo "<div class='alert alert-danger' role='alert'> Fail to Create User. 
            // </div>";
        }
    }

    ?>


    <!-- $_SERVER['PHP_SELF'] หมายถึง เข้าถึงตำแหน่งไฟล์ปัจจุบัน -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label"> Name</label>
            <input type="text" name="name" class="form-control" aria-describedby="name" placeholder="enter your name">
        </div>
        <div class="mb-3">
            <label for="email address" class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" aria-describedby="email" placeholder="enter your email">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" aria-describedby="password" placeholder="enter your password">
        </div>
        <div class="mb-3">
            <label for="confirm password" class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" aria-describedby="confirm password" placeholder="Confirm your password">
        </div>
        <button type="submit" name="signup" class="btn btn-primary">Sign Up</button>
    </form>
    <p class="mt-3">Already have an account yet? go to <a href="signin.php">Sign In</a></p>
    <hr>
    <a href="index.php" class="btn btn-secondary">Go back</a>
</div>

<?php include_once('footer.php'); ?>