<?php
session_start();
if(isset($_SESSION['user'])){
    header("location: ../index.php");
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="output.css">
    <title>Register</title>
</head>
<body>
    <div class="w-2/5 mx-auto m-12">
        <div class="bg-gray-300 m-6 rounded-lg">
            <div class="flex justify-between items-center px-16 pt-2">
                <h1 class="font-semibold py-3 text-xl text-center">Register Page</h1>
                <a href="./login.php" class="bg-red-600 py-2 px-3 font-semibold text-white rounded-lg">Login</a>
            </div>
            <form action="../back/registerConfig.php" method="post" class="py-4">
                <div class="flex flex-col items-center">
                    <div class="p-2 w-4/5 flex flex-col gap-2">
                        <label for="">Name:</label>
                        <input class="p-1 border-4 border-gray-400 rounded-lg" value="<?php echo $_POST['name'] ?? '' ?>" type="text" name="name" >
                        <?php echo isset($_SESSION['error']['name']) ? "<p class='text-red-500 w-full my-1'> ".$_SESSION['error']['name']. "</p>" : ""; ?>
                    </div>
                    <div class="p-2 w-4/5 flex flex-col gap-2">
                        <label for="">Email:</label>
                        <input class="p-1 border-4 border-gray-400 rounded-lg" value="<?php echo $_POST['email'] ?? '' ?>" type="text" name="email" >
                        <?php echo isset($_SESSION['error']['email']) ? "<p class='text-red-500 w-full my-1'> ".$_SESSION['error']['email']. "</p>" : ""; ?>
                    </div>
                    <div class="p-2 w-4/5 flex flex-col gap-2">
                        <label for="">Username:</label>
                        <input class="p-1 border-4 border-gray-400 rounded-lg" type="text" value="<?php echo $_POST['user_name'] ?? '' ?>" name="user_name" >
                        <?php echo isset($_SESSION['error']['user_name']) ? "<p class='text-red-500 w-full my-1'> ".$_SESSION['error']['user_name']. "</p>" : ""; ?>
                    </div>
                    <div class="p-2 w-4/5 flex flex-col gap-2">
                        <label for="">Password:</label>
                        <input class="p-1 border-4 border-gray-400 rounded-lg" type="password" name="password" >
                        <?php echo isset($_SESSION['error']['password']) ? "<p class='text-red-500 w-full my-1'> ".$_SESSION['error']['password']. "</p>" : ""; ?>
                    </div>
                    <div class="p-2 flex flex-col gap-2">
                       <button class="bg-blue-600 py-2 px-3 font-semibold text-white border-2 border-blue-800 rounded-lg" name="register">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</body>
</html>