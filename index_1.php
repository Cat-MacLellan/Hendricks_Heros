<?php

require_once('dbconn.php');

$sth = $dbconn->prepare("SELECT * FROM music order by TrackID asc");

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
  <div class="container">
    <h2>Hendricks Heroes Music Database</h2>
    <div class="success"></div>
    <div class="error"></div>
    <h2>Add / Edit Records</h2>
    <form>
       <table>
        <tr>
          <td colspan="10" style="text-align: center">
          <input type="hidden" id ='TrackID' value='' />
          <input type='text' id='Artist_Name' placeholder='artist name' required />&nbsp;&nbsp;
          <input type='text' id='Song_Title' placeholder='song title' required />&nbsp;&nbsp;
          <input type='text' id='Album_Name' placeholder='album name' required />&nbsp;&nbsp;
          <input type='text' id='Track_Length' placeholder='track length' required />&nbsp;&nbsp;
          <input type='text' id='Release_Date' placeholder='release date' required />&nbsp;&nbsp;
          <input type='text' id='GenreID' placeholder='genre ID' required />&nbsp;&nbsp;
          <input type='text' id='RecordLabelID' placeholder='record label ID' required />&nbsp;&nbsp;
          <input type='text' id='HIghest_Chart_Position' placeholder='highest chart position' required />&nbsp;&nbsp;
          <input type='Available_On_CD' id='category' placeholder='available on CD' required />&nbsp;&nbsp;
          <input type='button' id='saverecords'  value ='Add Records' /></td>
        </tr>
      </table>
    </form>
    <h2>View Records</h2>
    <table>
      <tr>
        <th>Track ID</th>
        <th>Artist Name</th>
        <th>Song Title</th>
        <th>Album Name</th>
        <th>Track Length</th>
        <th>Release Date</th>
        <th>Genre ID</th>
        <th>Record Label ID</th>
        <th>Highest Chart Position</th>
        <th>Available On CD</th>
        <th>Action</th>
      </tr>
  <?php
  /* FetchAll foreach with edit and delete using Ajax */
  if($sth->rowCount()):
   foreach($result as $row){ ?>
     <tr>
       <td><?php echo $row['TrackID']; ?></td>
       <td><?php echo $row['Artist_Name']; ?></td>
       <td><?php echo $row['Song_Title']; ?></td>
       <td><?php echo $row['Album_Name']; ?></td>
       <td><?php echo $row['Track_Length']; ?></td>
       <td><?php echo $row['Release_Date']; ?></td>
       <td><?php echo $row['GenreID']; ?></td>
       <td><?php echo $row['RecordLabelID']; ?></td>
       <td><?php echo $row['Highest_Chart_Position']; ?></td>
       <td><?php echo $row['Available_On_CD']; ?></td>
       
       
       <td><a data-pid = <?php echo $row['TrackID']; ?> class='editbtn' href= 'javascript:void(0)'>Edit</a>&nbsp;|&nbsp;<a class='delbtn' data-pid=<?php echo $row['TrackID']; ?> href='javascript:void(0)'>Delete</a></td>
     </tr>
   <?php }  ?>
  <?php endif;  ?>
  </table>
  </div>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script>
    $(function(){

      /* Delete button ajax call */
      $('.delbtn').on( 'click', function(){
        if(confirm('This action will delete this record. Are you sure?')){
          var pid = $(this).data('pid');
          $.post( "delete_ajax.php", { pid: pid })
          .done(function( data ) {
            if(data > 0){
              $('.success').show(3000).html("Record deleted successfully.").delay(3200).fadeOut(6000);
            }else{
              $('.error').show(3000).html("Record could not be deleted. Please try again.").delay(3200).fadeOut(6000);;
            }
            setTimeout(function(){
                window.location.reload(1);
            }, 5000);
          });
        }
      });

     /* Edit button ajax call */
      $('.editbtn').on( 'click', function(){
          var pid = $(this).data('pid');
          $.get( "getrecord_ajax.php", { id: pid })
            .done(function( product ) {
              data = $.parseJSON(product);

              if(data){
                $('#TrackID').val(data.TrackID);
                $('#Artist_Name').val(data.Artist_Name);
                $('#Song_Title').val(data.Song_Title);
                $('#Album_Name').val(data.Album_Name);
                $('#Track_Length').val(data.Track_Length); 
                $('#Release_Date').val(data.Release_Date); 
                $('#GenreID').val(data.GenreID);
                $('#RecordLabelID').val(data.RecordLabelID);
                $('#Highest_Chart_Position').val(data.Highest_Chart_Position);
                $('#Available_On_CD').val(data.Available_On_CD);
                $("#saverecords").val('Save Records');
            }
          });
      });

      /* Edit button ajax call */
       $('#saverecords').on( 'click', function(){
           var TrackID  = $('#TrackID').val();
           var Artist_Name = $('#Artist_Name').val();
           var Song_Title   = $('#Song_Title').val();
           var Album_Name = $('#Album_Name').val();
           var Track_Length = $('#Track_Length').val();
           var Release_Date = $('#Release_Date').val();
           var GenreID = $('#GenreID').val();
           var RecordLabelID = $('#RecordLabelID').val();
           var Highest_Chart_Position = $('#Highest_Chart_Position').val();
           var Available_On_CD = $('#Available_On_CD').val();
           
           if(!Artist_Name||!Song_Title||!Album_Name||!Track_Length||!Release_Date||!GenreID||!RecordlabelID||!Highest_Chart_Position||!Available_On_CD) {
             $('.error').show(3000).html("All fields are required.").delay(3200).fadeOut(3000);
           }else{
                if(TrackID){
                var url = 'edit_record_ajax.php';
              }else{
                var url = 'add_records_ajax.php';
              }
                $.post( url, {artist_name:artist_name, song_title:song_title, album_name:album_name, track_length:track_length,
                release_date:release_date, genreID:genreID, recordlabelID:recordlabelID, highest_chart_position:highest_chart_position,
                available_on_CD:available_on_CD})
               .done(function( data ) {
                 if(data > 0){
                   $('.success').show(3000).html("Record saved successfully.").delay(2000).fadeOut(1000);
                 }else{
                   $('.error').show(3000).html("Record could not be saved. Please try again.").delay(2000).fadeOut(1000);
                 }
                 $("#saverecords").val('Add Records');
                 setTimeout(function(){
                     window.location.reload(1);
                 }, 15000);
             });
          }
       });
    });
 </script>
</body>
</html>

