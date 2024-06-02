<?php include_once('header.php'); ?>

<?php include_once('nav.php'); ?>




<div class="container">

    <?php

    if (!isset($_SESSION['userid'])) {
        header("Location: signin.php");
    }

    include_once("config/Database.php");
    include_once("class/Userlogin.php");

    $connectDB = new Database();
    $db = $connectDB->getConnection();

    $user = new UserLogin($db);

    if (isset($_SESSION['userid'])) {
        $userid = $_SESSION['userid'];
        $userDeta = $user->userDeta($userid);
    }

    ?>

    <h1 class="display-4">Welcome User,<?php echo $userDeta['name'] ?></h1>
    <p>Your Email: <?= $userDeta['email']; ?></p>
    <hr>
    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Vitae incidunt culpa neque odit corporis, expedita ducimus! Explicabo maxime nemo totam doloribus iste ex corporis labore voluptatem minima, asperiores, facere quasi.</p>

</div>

<?php include_once('footer.php'); ?>