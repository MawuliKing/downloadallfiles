<?php
require_once "dbconn.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHARE BUTTON</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">

    <!-- owl carousel theme.css link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <script src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
</head>

<body>


    <div class="container">

        <?php
        if (isset($_POST['submit_post'])) {

            if (empty($_POST['title']) || empty($_POST['content'])) {
                echo "Please fill the form";
            } else {
                $title = htmlentities($_POST['title']);
                $content = htmlentities($_POST['content']);


                $insertQry = 'INSERT INTO post (title, content) VALUES (?,?)';

                $insertStatement = mysqli_prepare($conn, $insertQry);

                mysqli_stmt_bind_param($insertStatement, 'ss', $title, $content);

                if (mysqli_stmt_execute($insertStatement)) {


                    $post_id = mysqli_insert_id($conn);
                    // image script
                    $imgCount = count($_FILES['img']['name']);
                    for ($i = 0; $i < $imgCount; $i++) {

                        $imageName = $_FILES['img']['name'][$i];
                        $imageTmpName = $_FILES['img']['tmp_name'][$i];

                        $imageExt = pathinfo($imageName, PATHINFO_EXTENSION);;
                        $imageActualExt = strtolower($imageExt);
                        $imageNewName = uniqid('', true) . "." . $imageActualExt;


                        $imageDestination = "upload/$imageNewName";

                        if (strlen($imageActualExt) < 1) {
                        } else {
                            $insertQry = "INSERT INTO images(post, file_url) VALUES (?,?)";

                            $insertStatement = mysqli_prepare($conn, $insertQry);

                            mysqli_stmt_bind_param($insertStatement, 'ss', $post_id, $imageNewName);

                            if (mysqli_stmt_execute($insertStatement)) {
                                echo '<i class="fa-solid fa-check text-success"></i>';
                            } else {
                                echo '<i class="fa-solid fa-xmark text-danger"></i>';
                            }

                            move_uploaded_file($imageTmpName, $imageDestination);
                        }
                    }


                    $vidCount = count($_FILES['vid']['name']);
                    for ($i = 0; $i < $vidCount; $i++) {

                        $videoName = $_FILES['vid']['name'][$i];
                        $videoTmpName = $_FILES['vid']['tmp_name'][$i];

                        $videoExt = pathinfo($videoName, PATHINFO_EXTENSION);;
                        $videoActualExt = strtolower($videoExt);
                        $videoNewName = uniqid('', true) . "." . $videoActualExt;


                        $videoDestination = "upload/$videoNewName";

                        if ($_FILES['vid']['size'][$i] > 10000000) {
                            echo '<i class="fa-solid fa-xmark text-danger"></i> Large File size';
                        } else {
                            if (strlen($videoActualExt < 1)) {
                            } else {
                                $insertQry = "INSERT INTO video (post, file_url) VALUES (?,?)";

                                $insertStatement = mysqli_prepare($conn, $insertQry);

                                mysqli_stmt_bind_param($insertStatement, 'ss', $post_id, $videoNewName);

                                if (mysqli_stmt_execute($insertStatement)) {
                                    echo '<i class="fa-solid fa-check text-success"></i>';
                                } else {
                                    echo '<i class="fa-solid fa-xmark text-danger"></i>';
                                }

                                move_uploaded_file($videoTmpName, $videoDestination);
                            }
                        }
                    }



                    // echo "Story uploade";
                } else {
                    echo "Error";
                }
            }
        }
        ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <input name="title" type="text" placeholder="Post title" class="form-control"><br>
            <textarea name="content" cols="30" rows="10" class="form-control" placeholder="Post Content"></textarea><br>
            <div class="row">
                <div class="col-md-1">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="file" id="img" name="img[]" accept="image/*" multiple hidden />
                            <label for="img" id="img_label"><i class="fa-solid fa-file-image"></i></label>
                        </div>
                        <div class="col-md-6">
                            <input type="file" id="vid" name="vid[]" accept="video/*" multiple hidden />
                            <label for="vid"><i class="fa-solid fa-file-video"></i></label>
                        </div>
                    </div>

                </div>
            </div>
            <hr>
            <input type="submit" value="SUBMIT" name="submit_post" class="btn btn-primary">


        </form>
    </div>

    <hr>
    <div class="container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="text-center">
                <?php
                $getPost = mysqli_query($conn, "SELECT * FROM post ORDER BY id DESC");
                while ($row = mysqli_fetch_array(($getPost))) {

                    $postId = $row['id'];

                    $getImages = mysqli_query($conn, "SELECT * FROM images WHERE post = '$postId';");

                    $getVideos = mysqli_query($conn, "SELECT * FROM video WHERE post = '$postId';");

                    $countImg = mysqli_num_rows($getImages);
                    $countVid = mysqli_num_rows($getVideos);

                    if ($countImg >= 1 && $countVid >= 1) {
                        require_once 'BothOfThem.php';
                    } elseif ($countImg == "0" && $countVid >= 1) {
                        require_once 'videoOnly.php';
                    } elseif ($countImg >= 1 && $countVid == "0") {
                        require_once 'imageOnly.php';
                    } elseif ($countImg == "0" && $countVid == "0") {
                ?>
                        <div class='container'>
                            <h1> <?php echo $row['title']; ?></h1>
                            <p><?php echo $row['content']; ?></p>
                            <p><?php echo $row['dateadd']; ?></p>
                            <button onclick="SharePost('?postId=<?php echo $row['id']; ?>','<?php echo $row['title']; ?>')">Share this Story</button>
                        </div>
                        <hr>
                <?php
                    }
                }

                ?>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
    </div>





    <!-- This is a fall back function if the share does not work -->
    <div class="overlay"></div>
    <div class="share">
        <h2>Share Now</h2>
        <button>Social Link</button>
        <button>Social Link</button>
        <button>Social Link</button>
    </div>



    <script>
        const overlay = document.querySelector('.overlay');
        overlay.addEventListener('click', () => {
            overlay.classList.remove('show-share');
            sahreModal.classList.remove('show-share');
        });
    </script>


    <script>
        function SharePost(url, title) {
            if (navigator.share) {
                navigator.share({
                        title: `${title}`,
                        url: `${url}`
                    })
                    .then(() => {
                        console.log(`Thanks for sharing`);
                    })
                    .catch(console.error);
            } else {
                overlay.classList.add('show-share');
                sahreModal.classList.add('show-share');
            }
        }
    </script>

    <script>
        $(document).ready(() => {
            $('#img').change(() => {
                const num_of_imgs = $('#img')[0].files.length;
                // console.log(num_of_imgs);

                if (num_of_imgs > 5) {
                    alert(`You can select max 5 images`);
                    $('#img').val("");
                    $(".fa-file-image").text(" ");

                } else {
                    $(".fa-file-image").text(`${num_of_imgs}`);
                }
            })
        })


        $(document).ready(() => {
            $('#vid').change(() => {
                const num_of_vids = $('#vid')[0].files.length;
                // console.log(num_of_imgs);

                if (num_of_vids > 3) {
                    alert(`You can select max 3 videos`);
                    $('#img').val("");
                    $(".fa-file-video").text(" ")

                } else {
                    $(".fa-file-video").text(`${num_of_vids}`)
                }
            })
        })
    </script>
</body>

</html>