<?php
//Parent

require_once('dbconn.php');

$sth = $dbconn->prepare("SELECT Artist_Name, Song_Title, Genre_Title FROM Genre INNER JOIN music ON genre.GenreID = music.GenreID");

$sth->execute();
/* Fetch all of rows in the result set */
$result = $sth->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<style>
.container{
  margin: 20px auto;
}
h2 {
  text-align: center;
}
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}

body{
  font-family:Arial, Helvetica, sans-serif;
  font-size:13px;
}
.success, .error{
  border: 1px solid;
  margin: 10px 0px;
  padding:15px 10px 15px 50px;
  background-repeat: no-repeat;
  background-position: 10px center;
}

.success {
  color: #4F8A10;
  background-color: #DFF2BF;
  background-image:url('success.png');
  display: none;
}
.error {
  display: none;
  color: #D8000C;
  background-color: #FFBABA;
  background-image: url('error.png');
}
</style>
</head>
<body>
    <h2>View Records</h2>
    <table>
      <tr>
        <th>Artist Name</th>
        <th>Song Title</th>
        <th>Genre</th>
      </tr>
  <?php
  /* FetchAll foreach with edit and delete using Ajax */
  if($sth->rowCount()):
   foreach($result as $row){ ?>
     <tr>
       <td><?php echo $row['Artist_Name']; ?></td>
       <td><?php echo $row['Song_Title']; ?></td>
       <td><?php echo $row['Genre_Title']; ?></td>
     </tr>
   <?php }  ?>
  <?php endif;  ?>
  </table>
  </div>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script>
  