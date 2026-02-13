<?php
if (!isset($_SESSION)) {
    session_start();
}
include('../dbConnection.php');

if (isset($_SESSION['is_login'])) {
    $stuEmail = $_SESSION['stuLogEmail'];
} else {
    echo "<script> location.href='../index.php'; </script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Watch Course</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="../css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/stustyle.css">

    <style>
        #videoarea-container, #pdfarea {
            width: 48%; /* Adjust as needed for side-by-side layout */
            display: inline-block;
            vertical-align: top;
        }

        #videoarea {
            width: 100%; /* Full width within its container */
            height: auto;
        }

        #pdfarea embed {
            width: 100%;
            height: 600px;
        }
    </style>
</head>
<body>

    <div class="container-fluid bg-success p-2">
        <h3>Welcome to E-Learning</h3>
        <a class="btn btn-danger" href="./myCourse.php">My Courses</a>
    </div>
   
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 border-right">
                <h4 class="text-center">Lessons</h4>
                <ul id="playlist" class="nav flex-column">
                <?php
                if (isset($_GET['course_id'])) {
                    $course_id = $_GET['course_id'];
                    $sql = "SELECT * FROM lesson WHERE course_id = '$course_id'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Get lesson details
                            $lessonLink = $row['lesson_link']; // Video link
                            $lessonName = $row['lesson_name'];
                            $lessonReferences = $row['lesson_references']; // PDF link

                            echo '<li class="nav-item border-bottom py-2" 
                                movieurl="' . $lessonLink . '" 
                                pdfurl="' . $lessonReferences . '" 
                                style="cursor: pointer;">' . $lessonName . '</li>';
                        }
                    }
                }
                ?> 
                </ul>
            </div>

            <div class="col-sm-8">
                <!-- Video Player -->
                <div id="videoarea-container">
                    <video id="videoarea" src="" class="mt-5 w-75 ml-2" controls></video>
                </div>

                <!-- PDF Viewer -->
                <div id="pdfarea" class="mt-4">
                    <!-- PDF content will be embedded here dynamically -->
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery and Bootstrap JavaScript -->
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/popper.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>

    <!-- Font Awesome JS -->
    <script type="text/javascript" src="../js/all.min.js"></script>

    <!-- Custom JavaScript -->
    <script type="text/javascript">
    $(document).ready(function () {
        // When a lesson is clicked in the playlist
        $('#playlist li').on('click', function () {
            var videoUrl = $(this).attr('movieurl'); // Get the video URL
            var pdfUrl = $(this).attr('pdfurl'); // Get the PDF URL

            // Reset content areas
            $('#videoarea').hide();
            $('#pdfarea').empty();

            // If a video URL exists, display the video
            if (videoUrl) {
                $('#videoarea').attr('src', videoUrl).show();
                $('#videoarea')[0].play(); // Play the video automatically
            }

            // If a PDF URL exists, embed the PDF
            if (pdfUrl) {
                $('#pdfarea').html(
                    `<embed src="${pdfUrl}" type="application/pdf" width="100%" height="600px">`
                );
            }
        });

        // Set default content (first lesson) on page load
        var firstLesson = $('#playlist li').eq(0);
        if (firstLesson.length) {
            $('#playlist li').eq(0).trigger('click');
        }
    });
    </script>

</body>
</html>
