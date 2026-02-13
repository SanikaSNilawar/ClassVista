<?php 
if(!isset($_SESSION)){ 
  session_start(); 
}
define('TITLE', 'Edit Lesson');
define('PAGE', 'lessons');

include('./adminInclude/header.php'); 
include('../dbConnection.php');

 if(isset($_SESSION['is_admin_login'])){
  $adminEmail = $_SESSION['adminLogEmail'];
 } else {
  echo "<script> location.href='../index.php'; </script>";
 }
 // Update
 if(isset($_REQUEST['requpdate'])){
  // Checking for Empty Fields
  if(($_REQUEST['lesson_id'] == "") || ($_REQUEST['lesson_name'] == "") || ($_REQUEST['lesson_desc'] == "") || ($_REQUEST['course_id'] == "") || ($_REQUEST['course_name'] == "")){
   // msg displayed if required field missing
   $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> Fill All Fileds </div>';
  } else {
    // Assigning User Values to Variable
    $lid = $_REQUEST['lesson_id'];
    $lname = $_REQUEST['lesson_name'];
    $ldesc = $_REQUEST['lesson_desc'];
    $cid = $_REQUEST['course_id'];
    $cname = $_REQUEST['course_name'];
    //$llink = '../lessonvid/'. $_FILES['lesson_link']['name'];
    
   //$sql = "UPDATE lesson SET lesson_id = '$lid', lesson_name = '$lname', lesson_desc = '$ldesc', course_id='$cid', course_name='$cname', lesson_link='$llink' WHERE lesson_id = '$lid'";
    
   $llink = '../lessonvid/'. $_FILES['lesson_link']['name'];
   $lref = '../lessonpdf/'. $_FILES['lesson_references']['name'];
   
   // Move uploaded files to their respective directories
   if (!empty($_FILES['lesson_link']['name'])) {
     move_uploaded_file($_FILES['lesson_link']['tmp_name'], $llink);
   }
   if (!empty($_FILES['lesson_references']['name'])) {
     move_uploaded_file($_FILES['lesson_references']['tmp_name'], $lref);
   }
   
   // Update query to include lesson_references
   $sql = "UPDATE lesson SET 
     lesson_id = '$lid', 
     lesson_name = '$lname', 
     lesson_desc = '$ldesc', 
     course_id='$cid', 
     course_name='$cname', 
     lesson_link='$llink', 
     lesson_references='$lref' 
   WHERE lesson_id = '$lid'";
   

   if($conn->query($sql) == TRUE){
     // below msg display on form submit success
     $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Updated Successfully </div>';
    } else {
     // below msg display on form submit failed
     $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to Update </div>';
    }
  }
  }
 ?>
<div class="col-sm-6 mt-5  mx-3 jumbotron">
  <h3 class="text-center">Update Lesson Details</h3>
  <?php
 if(isset($_REQUEST['view'])){
  //$sql = "SELECT * FROM lesson WHERE lesson_id = {$_REQUEST['id']}";
  $sql = "SELECT lesson_id, lesson_name, lesson_desc, course_id, course_name, lesson_link, lesson_references FROM lesson WHERE lesson_id = {$_REQUEST['id']}";


 $result = $conn->query($sql);
 $row = $result->fetch_assoc();
 }
 ?>
  <form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label for="lesson_id">Lesson ID</label>
      <input type="text" class="form-control" id="lesson_id" name="lesson_id" value="<?php if(isset($row['lesson_id'])) {echo $row['lesson_id']; }?>" readonly>
    </div>
    <div class="form-group">
      <label for="lesson_name">Lesson Name</label>
      <input type="text" class="form-control" id="lesson_name" name="lesson_name" value="<?php if(isset($row['lesson_name'])) {echo $row['lesson_name']; }?>">
    </div>

    <div class="form-group">
      <label for="lesson_desc">Lesson Description</label>
      <textarea class="form-control" id="lesson_desc" name="lesson_desc" row=2><?php if(isset($row['lesson_desc'])) {echo $row['lesson_desc']; }?></textarea>
    </div>
    <div class="form-group">
      <label for="course_id">Course ID</label>
      <input type="text" class="form-control" id="course_id" name="course_id" value="<?php if(isset($row['course_id'])) {echo $row['course_id']; }?>" readonly>
    </div>
    <div class="form-group">
      <label for="course_name">Course Name</label>
      <input type="text" class="form-control" id="course_name" name="course_name" onkeypress="isInputNumber(event)" value="<?php if(isset($row['course_name'])) {echo $row['course_name']; }?>" readonly>
    </div>
    <div class="form-group">
      <label for="lesson_link">Lesson Link</label>
      <div class="embed-responsive embed-responsive-16by9">
       <iframe class="embed-responsive-item" src="<?php if(isset($row['lesson_link'])) {echo $row['lesson_link']; }?>" allowfullscreen></iframe>
      </div>     
      <input type="file" class="form-control-file" id="lesson_link" name="lesson_link">
    </div>
    <div class="form-group">
  <label for="lesson_references">Lesson References (PDF)</label>
  
  <!-- Display the current reference PDF in an iframe -->
  <?php if(isset($row['lesson_references']) && !empty($row['lesson_references'])): ?>
    <div class="embed-responsive embed-responsive-16by9">
      <iframe class="embed-responsive-item" src="<?php echo $row['lesson_references']; ?>" allowfullscreen></iframe>
    </div>
  <?php else: ?>
    <p>No references uploaded</p>
  <?php endif; ?>
  
  <!-- Input for uploading a new PDF -->
  <input type="file" class="form-control-file" id="lesson_references" name="lesson_references">
</div>


    <div class="text-center">
      <button type="submit" class="btn btn-danger" id="requpdate" name="requpdate">Update</button>
      <a href="lessons.php" class="btn btn-secondary">Close</a>
    </div>
    <?php if(isset($msg)) {echo $msg; } ?>
  </form>
</div>
</div>  <!-- div Row close from header -->
</div>  <!-- div Conatiner-fluid close from header -->

<?php
include('./adminInclude/footer.php'); 
?>