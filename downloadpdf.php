<?php require_once("TCPDF/tcpdf.php");
function generatepdf () {
    $con=mysqli_connect("localhost","root","","myhmsdb1");
    $pid = $_SESSION['pid'];
    $output='';
    
    $generatepdf = $_REQUEST["generatepdf"] ?? "";
    if($generatepdf == 'doctor') {
      $sql_query = "select * from doctb";
      $query=mysqli_query($con,$sql_query);
      $output .= '<table class="table table-hover" cellspacing="2" cellpadding="2">
        <thead>
          <tr>
            <th scope="col">Sr. No.</th>
            <th scope="col">Doctor Name</th>
            <th scope="col">Specialization</th>
            <th scope="col">Email</th>
            <th scope="col">Fees</th>
          </tr>
        </thead>';
      $i=1;while($row = mysqli_fetch_array($query)){
        $output .= '<tr><td>'.$i.'</td><td>'.$row["username"].'</td>
        <td>'.$row["email"].'</td>
        <td>'.$row["spec"].'</td>
        <td>'.$row["docFees"].'</td></tr>';
    
      $i++;
      }
    } else if($generatepdf == 'patient') {
      $sql_query = "select * from patreg";
      $result=mysqli_query($con,$sql_query);
      $output .= '<table class="table table-hover" cellspacing="2" cellpadding="2">
      <thead><tr><th scope="col">Sr. No.</th>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Gender</th>
            <th scope="col">Email</th>
            <th scope="col">Contact</th>
          </tr></thead>';
      $i=1; while ($row = mysqli_fetch_array($result)){
        $fname = $row['fname'];
        $lname = $row['lname'];
        $gender = $row['gender'];
        $email = $row['email'];
        $contact = $row['contact'];
        
        
        $output .= '<tr>
          <td>'.$i.'</td>
          <td>'.$fname.'</td>
          <td>'.$lname.'</td>
          <td>'.$gender.'</td>
          <td>'.$email.'</td>
          <td>'.$contact.'</td>
          
        </tr>';
      $i++;
      }
    } else {
      $sql_query = "select * from appointmenttb";
      $result=mysqli_query($con,$sql_query);
      $output .= '<table class="table table-hover" cellspacing="2" cellpadding="2">
            <thead>
              <tr>
                <th scope="col">Appointment ID</th>
                <th scope="col">Patient ID</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Gender</th>
                <th scope="col">Email</th>
                <th scope="col">Contact</th>
                <th scope="col">Doctor Name</th>
                <th scope="col">Consultancy Fees</th>
                <th scope="col">Appointment Date</th>
                <th scope="col">Appointment Time</th>
                <th scope="col">Appointment Status</th>
              </tr>
            </thead>';
            while ($row = mysqli_fetch_array($result)){
              $status = '';
              if(($row['userStatus']==1) && ($row['doctorStatus']==1))  
              {
                $status = "Active";
              }
              if(($row['userStatus']==0) && ($row['doctorStatus']==1))  
              {
                $status = "Cancelled by Patient";
              }

              if(($row['userStatus']==1) && ($row['doctorStatus']==0))  
              {
                $status = "Cancelled by Doctor";
              }

              $output .= '<tr>
                <td>'.$row["ID"].'</td>
                <td>'.$row["pid"].'</td>
                <td>'.$row["fname"].'</td>
                <td>'.$row["lname"].'</td>
                <td>'.$row["gender"].'</td>
                <td>'.$row["email"].'</td>
                <td>'.$row["contact"].'</td>
                <td>'.$row["doctor"].'</td>
                <td>'.$row["docFees"].'</td>
                <td>'.$row["appdate"].'</td>
                <td>'.$row["apptime"].'</td>
                <td>'.$status.'</td>
              </tr>';
            }
    }
    
    $output .= '</table>';
  
  return $output;
}
$pdftitle = '';
$generatepdf = $_REQUEST["generatepdf"] ?? "";
if($generatepdf == 'doctor') {
  $pdftitle = 'Doctor List';
} else if($generatepdf == 'patient') {
  $pdftitle = 'Patient List';
} else {
  $pdftitle = 'Appointment List';
}
  $obj_pdf = new TCPDF('P',PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
  $obj_pdf -> SetCreator(PDF_CREATOR);
  $obj_pdf -> SetTitle($pdftitle);
  $obj_pdf -> SetHeaderData('','',PDF_HEADER_TITLE,PDF_HEADER_STRING);
  $obj_pdf -> SetHeaderFont(Array(PDF_FONT_NAME_MAIN,'',PDF_FONT_SIZE_MAIN));
  $obj_pdf -> SetFooterFont(Array(PDF_FONT_NAME_MAIN,'',PDF_FONT_SIZE_MAIN));
  $obj_pdf -> SetDefaultMonospacedFont('helvetica');
  $obj_pdf -> SetFooterMargin(PDF_MARGIN_FOOTER);
  $obj_pdf -> SetMargins(PDF_MARGIN_LEFT,'5',PDF_MARGIN_RIGHT);
  $obj_pdf -> SetPrintHeader(false);
  $obj_pdf -> SetPrintFooter(false);
  $obj_pdf -> SetAutoPageBreak(TRUE, 10);
  $obj_pdf -> SetFont('helvetica','',12);
  $obj_pdf -> AddPage();

  $content = '';

  $content .= '
      <br/>
      <h2 align ="center"> Kunwar Shekhar Vijendra Ayurved Medical College & Research Center</h2></br>
      <h3 align ="center"> '.$pdftitle.'</h3>
      

  ';
 
  $content .= generatepdf();
  $obj_pdf -> writeHTML($content);
  ob_end_clean();
  return $obj_pdf -> Output("list.pdf",'I');?>