<?php
session_start();
require_once "./database/connection.php";

if (!isset($_SESSION['user'])){
    header('Location: ./front/login.php');
    exit();
}elseif ($_SESSION['user']->role == 'admin' and $_SESSION['user']['status'] !== 1){
    header('Location: ./front/adminUser.php');
    exit();
}

if (isset($_POST['logout'])){
    session_unset();
    header('location: ./front/login.php');
    exit();
}


//$chatData = json_decode(file_get_contents('./database/messages.json') , JSON_FORCE_OBJECT) ?? "";

$stmtMsg = $conn->prepare('SELECT * FROM messages');
$stmtMsg->execute();
$stmtMsgRes = $stmtMsg->fetchAll(5);

if (!empty($stmtMsgRes)){
    array_multisort(array_column($stmtMsgRes , 'date') , SORT_ASC , $stmtMsgRes );
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.css" integrity="sha512-0Nyh7Nf4sn+T48aTb6VFkhJe0FzzcOlqqZMahy/rhZ8Ii5Q9ZXG/1CbunUuEbfgxqsQfWXjnErKZosDSHVKQhQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.js" integrity="sha512-aGWPnmdBhJ0leVHhQaRASgb0InV/Z2BWsscdj1Vwt29Oic91wECPixuXsWESpFfCcYPLfOlBZzN2nqQdMxlGTQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>Chat</title>
</head>
<body>
    <div class="w-3/5 bg-gray-300 p-5 rounded-lg mx-auto mt-24">
        <div class="p-3 flex justify-between items-center">
            <h1 class="font-semibold">Welcome, <span class="text-blue-900"><?php echo $_SESSION['user']->name ?></span></h1>
            <div class="flex gap-2">
                <form action="" method="post">
                    <button class="text-white py-2 px-3 bg-red-600 rounded-lg" name="logout">Log Out</button>
                </form>
                <a href="./front/profile.php" class="bg-green-600 py-2 px-3 rounded-lg text-white">Profile</a>
            </div>
        </div>
        <div class="flex flex-col bg-white w-3/5 rounded-lg overflow-y-auto relative bottom-0 h-96 shadow-lg p-3 mx-auto">

            <?php
            if (empty($stmtMsgRes)){
            ?>
                <p class="flex items-center justify-center text-gray-800 font-semibold">No message here yet...</p>
            <?php
            }else{
                foreach ($stmtMsgRes as $key => $message) {
                    if ($message->user_id == $_SESSION['user']->id){
                        if (!is_null($message->image_id)){

                            $stmtImg = $conn->prepare('SELECT * FROM images WHERE id = :id');
                            $stmtImg->bindParam(':id', $message->image_id);
                            $stmtImg->execute();
                            $stmtImgRes = $stmtImg->fetch(5);

                            ?>
                            <div class="flex justify-end items-center w-full my-1">
                                <div class="">
                                    <form action="" method="post">
                                        <input type="hidden" name="edit_message" value="<?php echo $message->message ?>">
                                        <input type="hidden" name="message_id" value="<?php echo $message->id ?>">
                                        <button type="submit" name="edit" class="text-yellow-500 mx-1 hover:underline">edit</button>
                                    </form>
                                </div>
                                <div class="">
                                    <form action="./back/delete.php" method="post">
                                        <input type="hidden" name="delete_message_id" value="<?php echo $message->id ?>">
                                        <input type="hidden" name="delete_message_path" value="<?php echo $stmtImgRes->path ?>">
<!--                                        <input type="hidden" name="delete_message_user_name" value="--><?php //echo $message->user_id ?><!--">-->
                                        <button type="submit" name="delete" class="text-red-500 mx-1 hover:underline">delete</button>
                                    </form>
                                </div>
                                <div class="bg-gray-600 shadow-lg self-end text-white my-1 w-1/2 rounded-t-xl rounded-bl-xl">
                                    <img src="<?php echo './' . $stmtImgRes->path  ?>" class="w-full rounded-t-xl" alt="">
                                    <?php
                                        $stmtUsr = $conn->prepare('SELECT * FROM users WHERE id = :id');
                                        $stmtUsr->bindParam(':id' , $message->user_id);
                                        $stmtUsr->execute();
                                        $stmtUsrRes = $stmtUsr->fetch(5);

                                    ?>
                                    <p class="text-xs pt-0 pl-2 font-extralight"><?php echo $stmtUsrRes->name ?></p>
                                    <p class="break-words pl-2 pt-1 font-semibold text-white"><?php echo $message->message ?></p>
                                    <p class="text-xs pr-2 font-extralight float-right"><?php echo $message->date ?></p>
                                </div>
                            </div>
            <?php
                        }else{
            ?>
                            <div class="flex justify-end items-center w-full my-1">
                                <div class="">
                                    <form action="" method="post">
                                        <input type="hidden" name="edit_message" value="<?php echo $message->message ?>">
                                        <input type="hidden" name="message_id" value="<?php echo $message->id ?>">
                                        <button type="submit" name="edit" class="text-yellow-500 mx-1 hover:underline">edit</button>
                                    </form>
                                </div>
                                <div class="">
                                    <form action="./back/delete.php" method="post">
                                        <input type="hidden" name="delete_message_id" value="<?php echo $message->id ?>">
                                        <button type="submit" name="delete" class="text-red-500 mx-1 hover:underline">delete</button>
                                    </form>
                                </div>
                                <div class="bg-gray-600 shadow-lg self-end text-white my-1 w-1/2 rounded-t-xl rounded-bl-xl">
                                    <?php
                                    $stmtUsr = $conn->prepare('SELECT * FROM users WHERE id = :id');
                                    $stmtUsr->bindParam(':id' , $message->user_id);
                                    $stmtUsr->execute();
                                    $stmtUsrRes = $stmtUsr->fetch(5);

                                    ?>
                                    <p class="text-xs pt-0 pl-2 font-extralight"><?php echo  $stmtUsrRes->name ?></p>
                                    <p class="break-words pl-2 pt-1 font-semibold text-white"><?php echo $message->message ?></p>
                                    <p class="text-xs pr-2 font-extralight float-right"><?php echo $message->date ?></p>
                                </div>
                            </div>
            <?php
                        }
                    }else{
                        if (!is_null($message->image_id)){
            ?>
                            <div class="bg-blue-200 shadow-lg my-1 w-1/2 rounded-t-xl rounded-br-xl">
                                <?php
                                $stmtImg = $conn->prepare('SELECT * FROM images WHERE id = :id');
                                $stmtImg->bindParam(':id' , $message->image_id);
                                $stmtImg->execute();
                                $stmtImgRes = $stmtImg->fetch(5);
                                ?>
                                <img src="<?php echo './' . $stmtImgRes->path  ?>" class="w-full rounded-t-xl" alt="">
                                <?php
                                $stmtUsr = $conn->prepare('SELECT * FROM users WHERE id = :id');
                                $stmtUsr->bindParam(':id' , $message->user_id);
                                $stmtUsr->execute();
                                $stmtUsrRes = $stmtUsr->fetch(5);

                                ?>
                                <p class="text-xs pt-0 pl-2 font-extralight"><?php echo $stmtUsrRes->name ?></p>
                                <p class="break-words pl-2 pt-1 font-semibold text-gray-700"><?php echo $message->message ?></p>
                                <p class="text-xs pr-2 font-extralight float-right"><?php echo $message->date ?></p>
                            </div>
            <?php
                        }else{
            ?>
                            <div class="bg-blue-200 shadow-lg my-1 w-1/2 rounded-t-xl rounded-br-xl">
                                <?php
                                $stmtUsr = $conn->prepare('SELECT * FROM users WHERE id = :id');
                                $stmtUsr->bindParam(':id' , $message->user_id);
                                $stmtUsr->execute();
                                $stmtUsrRes = $stmtUsr->fetch(5);

                                ?>
                                <p class="text-xs pt-0 pl-2 font-extralight"><?php echo $stmtUsrRes->name ?></p>
                                <p class="break-words pl-2 pt-1 font-semibold text-gray-700"><?php echo $message->message ?></p>
                                <p class="text-xs pr-2 font-extralight float-right"><?php echo $message->date ?></p>
                            </div>
            <?php
                        }
                    }
                }
            }
            ?>
        </div>
        <?php
            if ($_SESSION['user']->status == 1){
        ?>
                <div class="flex items-center bg-white border-4 border-gray-400 w-3/5 rounded-lg shadow-lg my-3 mx-auto">
                    <p class="text-red-500 px-3 py-2 font-semibold">You can't send any message because you've been blocked by admin!</p>
                </div>
        <?php
            }else{
        ?>
            <form action="./back/sendConfig.php" method="post" enctype="multipart/form-data" class="flex items-center bg-white border-4 border-gray-400 w-3/5 rounded-lg shadow-lg my-3 mx-auto">
                <input id="myText" type="text" class="px-2 py-0.5 w-4/5 rounded-lg outline-none" name="message" value="<?php echo isset($_POST['edit']) ? $_POST['edit_message'] : '' ?>" placeholder="Write your message.">
                <input type="hidden" name="message_id" value="<?php echo isset($_POST['edit']) ? $_POST['message_id'] : ''?>">
                <input type="file" name="file" class="hidden" id="file">
                <label for="file" class="cursor-pointer ml-12">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="text-gray-600 w-6 h-6">
                        <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z" />
                        <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd" />
                    </svg>
                </label>
                <div class="flex ml-1">
                    <button type="submit" name="<?php echo isset($_POST['edit']) ? 'edit_btn' : 'send' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="text-gray-600 mr-0.5 w-6 h-6">
                            <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                        </svg>
                    </button>
                </div>
            </form>
        <?php } ?>
        <?php
        if (isset($_SESSION['error'])){
        ?>
            <div class="flex flex-col w-3/5 rounded-lg px-2 py-1 mx-auto">
                <?php echo isset($_SESSION['error']['message']) ? "<p class='text-red-500 w-full my-1'> ".$_SESSION['error']['message']. "</p>" : ""; ?>
                <?php echo isset($_SESSION['error']['file']) ? "<p class='text-red-500 w-full my-1'> ".$_SESSION['error']['file']. "</p>" : ""; ?>
            </div>
        <?php
        }
        ?>


    </div>

    <script>
        $('#myText').emojioneArea();
    </script>
</body>
</html>
