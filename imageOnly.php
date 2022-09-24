<div class='container'>
                    <h1> <?php echo $row['title']; ?></h1>
                    <div class="owl-carousel owl-theme" id="ioooi_<?php echo $row['id']; ?>">
                        <?php
                        $gIOOI = mysqli_query($conn, "SELECT * FROM images WHERE post = '" . $row['id'] . "';");
                        while ($giooirow = mysqli_fetch_array($gIOOI)) {
                            echo "<div class='item'> <img src='upload/" . $giooirow['file_url'] . "' /> </div>";
                        }
                        ?>
                    </div>
                    <p><?php echo $row['content']; ?></p>
                    <p><?php echo $row['dateadd']; ?></p>
                    <button onclick="SharePost('?postId=<?php echo $row['id']; ?>','<?php echo $row['title']; ?>')">Share this Story</button>
                    <script>
                        $('#ioooi_<?php echo $row['id']; ?>').owlCarousel({
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