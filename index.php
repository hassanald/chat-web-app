<?php
session_start();

if (!isset($_SESSION['user'])){
    header('Location: ./front/login.php');
    exit();
}
if (isset($_POST['logout'])){
    session_unset();
    header('location: ./front/login.php');
    exit();
}

$chatData = json_decode(file_get_contents('./database/messages.json') , JSON_FORCE_OBJECT) ?? "";
if (!empty($chatData)){
    array_multisort(array_column($chatData , 'date') , SORT_ASC , $chatData );
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="front/output.css">
    <title>Chat</title>
</head>
<body>
    <div class="w-3/5 bg-gray-300 p-5 rounded-lg mx-auto mt-24">
        <div class="p-3 flex justify-between items-center">
            <h1 class="font-semibold">Welcome, <span class="text-blue-900"><?php echo $_SESSION['user']['name'] ?></span></h1>
            <form action="" method="post">
                <button class="text-white py-2 px-3 bg-red-600 rounded-lg" name="logout">Log Out</button>
            </form>
        </div>
        <div class="flex flex-col bg-white w-3/5 rounded-lg overflow-y-auto relative bottom-0 h-96 shadow-lg p-3 mx-auto">

            <?php
            if (empty($chatData)){
            ?>
                <p class="flex items-center justify-center text-gray-800 font-semibold">No message here yet...</p>
            <?php
            }else{
                foreach ($chatData as $key => $message) {
                    if ($message['user_name'] == $_SESSION['user']['user_name']){
            ?>
                        <div class="flex justify-end items-center w-full my-1">
                            <div class="">
                                <form action="">
                                    <button type="submit" name="edit" class="text-yellow-500 mx-1 hover:underline">edit</button>
                                </form>
                            </div>
                            <div class="">
                                <form action="">
                                    <button type="submit" name="delete" class="text-red-500 mx-1 hover:underline">delete</button>
                                </form>
                            </div>
                            <div class="bg-gray-600 shadow-lg self-end text-white my-1 w-1/2 rounded-t-xl rounded-bl-xl">
                                <p class="text-xs pt-0 pl-2 font-extralight"><?php echo $message['user_name'] ?></p>
                                <p class="break-words pl-2 pt-1 font-semibold text-white"><?php echo $message['message'] ?></p>
                                <p class="text-xs pr-2 font-extralight float-right"><?php echo $message['date'] ?></p>
                            </div>
                        </div>
            <?php
                    }else{
            ?>
                        <div class="bg-blue-200 shadow-lg my-1 w-1/2 rounded-t-xl rounded-br-xl">
                            <p class="text-xs pt-0 pl-2 font-extralight"><?php echo $message['user_name'] ?></p>
                            <p class="break-words pl-2 pt-1 font-semibold text-gray-700"><?php echo $message['message'] ?></p>
                            <p class="text-xs pr-2 font-extralight float-right"><?php echo $message['date'] ?></p>
                        </div>
            <?php
                    }
                }
            }
            ?>
        </div>
        <form action="./back/sendConfig.php" method="post" enctype="multipart/form-data" class="flex items-center justify-between bg-white border-4 border-gray-400 w-3/5 rounded-lg shadow-lg my-3 mx-auto">
            <input type="text" class="px-2 py-0.5 w-4/5 rounded-lg outline-none" name="message" placeholder="Write your message.">
            <div class="flex">
                <button>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd" d="M19.902 4.098a3.75 3.75 0 00-5.304 0l-4.5 4.5a3.75 3.75 0 001.035 6.037.75.75 0 01-.646 1.353 5.25 5.25 0 01-1.449-8.45l4.5-4.5a5.25 5.25 0 117.424 7.424l-1.757 1.757a.75.75 0 11-1.06-1.06l1.757-1.757a3.75 3.75 0 000-5.304zm-7.389 4.267a.75.75 0 011-.353 5.25 5.25 0 011.449 8.45l-4.5 4.5a5.25 5.25 0 11-7.424-7.424l1.757-1.757a.75.75 0 111.06 1.06l-1.757 1.757a3.75 3.75 0 105.304 5.304l4.5-4.5a3.75 3.75 0 00-1.035-6.037.75.75 0 01-.354-1z" clip-rule="evenodd" />
                    </svg>
                </button>
                <button type="submit" name="send">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="text-gray-600 mr-0.5 w-6 h-6">
                        <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                    </svg>
                </button>
            </div>
        </form>
        <div class="flex flex-col w-3/5 rounded-lg px-2 py-1 mx-auto">
            <?php echo isset($_SESSION['error']['message']) ? "<p class='text-red-500 w-full my-1'> ".$_SESSION['error']['message']. "</p>" : ""; ?>
        </div>
    </div>
</body>
</html>
