<?php
session_start();
require_once "../database/connection.php";

if (!isset($_SESSION['user'])){
    header('Location: ../front/login.php');
    exit();
}


//$usersData = json_decode(file_get_contents('../database/users.json' ) , true);
//$loggedInUser = $usersData[$_SESSION['user']['email']];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./output.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <title>Profile</title>
</head>
<body>
    <div class="">
        <div class="mb-6 mt-20">
            <h1 class="font-semibold text-center text-xl" id="nameOfUser"><?php echo $_SESSION['user']->name ?>'s Profile</h1>
        </div>
        <div class="bg-gray-100 w-4/5 mt-2 shadow-lg rounded-lg flex justify-between items-center mx-auto">
            <div id="myContent" class="">
                <!--Content-->
            </div>
            <div class="w-full max-w-lg m-8">
                <form class="bg-white shadow-md rounded px-8 pt-6 pb-8" id="form" >
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                            Name:
                        </label>
                        <input class="shadow appearance-none border rounded w-full
                         py-2 px-3 text-gray-700 leading-tight focus:outline-none
                          focus:shadow-outline"  value="<?php echo $_SESSION['user']->name?>" id="username" name="name" type="text"
                               placeholder="Name">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="about_me">
                            About Me:
                        </label>
                        <textarea class="shadow appearance-none border rounded w-full
                         py-2 px-3 text-gray-700 leading-tight focus:outline-none
                          focus:shadow-outline" id="about_me" name="about_me"
                                  placeholder="About Me..."><?php echo $_SESSION['user']->about_me ?></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                            Image:
                        </label>
                        <input class="shadow appearance-none border rounded w-full
                         py-2 px-3 text-gray-700 leading-tight focus:outline-none
                          focus:shadow-outline" id="image" name="image" type="file">
                    </div>
                    <div class="">
                        <button class="py-1 px-3 bg-blue-600 text-white rounded-lg" id="submit" name="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function getProfile(){
            $.ajax({
                url: '../back/profileRes.php',
                success: function (response){
                    $('#myContent').html(response.view)
                }
            })
        }

        $(document).ready(function (){

            getProfile()

            //Send Data
            $('#form').submit(function (e){
                e.preventDefault();
                var formData = new FormData(document.getElementById('form'));

                $.ajax({
                    type: "POST",
                    url: "../back/profileConfig.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (){
                        $('#image').val('')
                        // console.log(this.data)
                        getProfile()
                    }
                });
            });
        })
    </script>
</body>
</html>
