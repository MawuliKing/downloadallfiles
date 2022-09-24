<div class='container'>
    <h1> <?php echo $row['title']; ?></h1>
    <div class="owl-carousel owl-theme" id="vooi_<?php echo $row['id']; ?>">
        <?php
        $gVOOV = mysqli_query($conn, "SELECT * FROM video WHERE post = '" . $row['id'] . "';");
        while ($gvoovrow = mysqli_fetch_array($gVOOV)) {
            // echo "<div class='item'> <img src='upload/" . $gvoovrow['file_url'] . "'  alt='...'/> </div>";
            echo "<div class='item'> 
                    <video width='320' height='240' controls>
                        <source src='upload/" . $gvoovrow['file_url'] . "' type='video/mp4'>
                        Error Message
                    </video>
                </div>";
        }
        ?>
    </div>
    <p><?php echo $row['content']; ?></p>
    <p><?php echo $row['dateadd']; ?></p>
    <button onclick="SharePost('?postId=<?php echo $row['id']; ?>','<?php echo $row['title']; ?>')">Share this Story</button>
    <script>
        $('#vooi_<?php echo $row['id']; ?>').owlCarousel({
            loop: false,
            margin: 10,
            nav: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        })
    </script>
</div>
<hr>