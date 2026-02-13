<?php
if (!isset($_SESSION)) {
    session_start();
}
define('TITLE', 'Lessons');
define('PAGE', 'lessons');
include('./adminInclude/header.php');
include('../dbConnection.php');

if (isset($_SESSION['is_admin_login'])) {
    $adminEmail = $_SESSION['adminLogEmail'];
} else {
    echo "<script> location.href='../index.php'; </script>";
}
?>

<div class="col-sm-9 mt-5 mx-3">
  <form action="" class="mt-3 form-inline d-print-none">
    <div class="form-group mr-3">
      <label for="checkid">Enter Course ID: </label>
      <input type="text" class="form-control ml-3" id="checkid" name="checkid" onkeypress="isInputNumber(event)">
    </div>
    <button type="submit" class="btn btn-danger">Search</button>
  </form>
  <?php
  $sql = "SELECT course_id FROM course";
  $result = $conn->query($sql);

  if ($result === false) {
      die("<div class='alert alert-danger'>Error fetching courses: " . $conn->error . "</div>");
  }

  while ($row = $result->fetch_assoc()) {
      if (isset($_REQUEST['checkid']) && $_REQUEST['checkid'] == $row['course_id']) {
          $sql = "SELECT * FROM course WHERE course_id = {$_REQUEST['checkid']}";
          $result = $conn->query($sql);

          if ($result === false) {
              die("<div class='alert alert-danger'>Error fetching course details: " . $conn->error . "</div>");
          }

          $row = $result->fetch_assoc();

          if (($row['course_id']) == $_REQUEST['checkid']) {
              $_SESSION['course_id'] = $row['course_id'];
              $_SESSION['course_name'] = $row['course_name'];
              ?>
              <h3 class="mt-5 bg-dark text-white p-2">
                  Course ID: <?php echo $row['course_id']; ?>
                  &nbsp;&nbsp;|&nbsp;&nbsp;
                  Course Name: <?php echo $row['course_name']; ?>
              </h3>
              <?php
              $sql = "SELECT lesson_id, lesson_name, lesson_link, lesson_references FROM lesson WHERE course_id = {$_REQUEST['checkid']}";
              $result = $conn->query($sql);

              if ($result === false) {
                  die("<div class='alert alert-danger'>Error fetching lessons: " . $conn->error . "</div>");
              }

              echo '<table class="table table-bordered table-hover mt-3">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">Lesson ID</th>
                    <th scope="col">Lesson Name</th>
                    <th scope="col">Lesson Link</th>
                    <th scope="col">Lesson References</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>';
              while ($row = $result->fetch_assoc()) {
                  echo '<tr>';
                  echo '<th scope="row">' . $row["lesson_id"] . '</th>';
                  echo '<td>' . $row["lesson_name"] . '</td>';
                  echo '<td>' . $row["lesson_link"] . '</td>';
                  echo '<td>' . (!empty($row["lesson_references"]) ? $row["lesson_references"] : 'No References') . '</td>';
                  echo '<td>
                        <form action="editlesson.php" method="POST" class="d-inline">
                          <input type="hidden" name="id" value=' . $row["lesson_id"] . '>
                          <button type="submit" class="btn btn-info mr-2" name="view" value="View">
                            <i class="fas fa-pen"></i>
                          </button>
                        </form>
                        <form action="" method="POST" class="d-inline">
                          <input type="hidden" name="id" value=' . $row["lesson_id"] . '>
                          <button type="submit" class="btn btn-secondary" name="delete" value="Delete">
                            <i class="far fa-trash-alt"></i>
                          </button>
                        </form>
                      </td>';
                  echo '</tr>';
              }
              echo '</tbody></table>';
          } else {
              echo '<div class="alert alert-dark mt-4" role="alert">Course Not Found!</div>';
          }
      }
  }
  ?>
</div>

<script>
  function isInputNumber(evt) {
      var ch = String.fromCharCode(evt.which);
      if (!(/[0-9]/.test(ch))) {
          evt.preventDefault();
      }
  }
</script>

<?php include('./adminInclude/footer.php'); ?>
