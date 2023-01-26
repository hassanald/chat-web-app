<div class="w-4/5 ml-16">
    <div class="mb-4 w-4/5 text-center mx-auto flex flex-wrap justify-center gap-1" id="imageOfUser">
        <!--images-->
        <?php
        foreach ($loggedInUser['images'] as $key => $image){
            ?>
            <div class="flex flex-col items-center justify-center">
                <button type="button" class="deletedImage" value="<?php echo $key ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </button>
                <img src="<?php echo '../' . $image['full_path'] ?>" class="w-24 rounded-lg h-24" alt="">
            </div>
            <?php
        }
        ?>
    </div>
    <div class="m-8 w-full max-w-lg bg-white shadow-md rounded px-8 pt-6 pb-8">
        <div class="mb-4">
            Name:
            <span class="font-semibold" id="secondNameOfUser"><?php echo $loggedInUser['name']?></span>
        </div>
        <div class="mb-4">
            About Me:
            <span class="font-semibold break-words" id="aboutMeOfUser"><?php echo $loggedInUser['about_me']?></span>
        </div>
    </div>
</div>
<script>
    //Delete Image
    $('.deletedImage').click(function (e){
        $(this).each(function (){
            let imageKey = this.value
            e.preventDefault()
            $.ajax({
                type: "POST",
                url: "../back/deleteProfileImage.php",
                data: {
                    'image_key': imageKey
                },
                success: function (){
                    getProfile()
                }
            })
        })
    })
</script>
