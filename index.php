<?php
require_once "pages/config.php";
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: pages/login.php");
    exit;
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>E-Leave - Dashboard</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/css/style.css">
</head>

<body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
    <!-- HIDDEN PREHEADER TEXT -->
    <div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: 'Lato', Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;"> We're thrilled to have you here! Get ready to dive into your new account. </div>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <!-- LOGO -->
        <tr>
            <td bgcolor="#246cb4" align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td align="center" valign="top" style="padding: 40px 10px 40px 10px;"> </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#246cb4" align="center" style="padding: 0px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                            <h1 style="font-size: 48px; font-weight: 400; margin: 2;">E-Leave Dashboard</h1> <img src=" https://www.epignosishq.com/wp-content/themes/epignosishq/dist/images/logo.svg" width="220" height="120" style="margin-top:30px ;display: block; border: 0px;" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                                Welcome to e-Leave, <?php
                                $sql = 'SELECT first_name, last_name FROM users WHERE email = ?';
                                if ($stmt = mysqli_prepare($link, $sql)) {
                                    // Bind variables to the prepared statement as parameters
                                    mysqli_stmt_bind_param($stmt, "s", $_SESSION["email"]);

                                    // Set parameters
                                    $param_email = $_SESSION["email"];
                                    // Attempt to execute the prepared statement
                                    if (mysqli_stmt_execute($stmt)) {
                                        /* store result */
                                        mysqli_stmt_bind_result($stmt, $first_name, $last_name);

                                        while (mysqli_stmt_fetch($stmt)) {
                                            printf("%s %s", $first_name, $last_name);
                                        }
                                        // Close statement

                                        mysqli_stmt_close($stmt);
                                    }
                                }
                                ?>.
                                  <div class="text-center"><a style="margin: 10px;" href="/pages/submit_request.php" class="btn btn-primary">Submit Request</a></div>
                          <?php
                          function format_date($date)
                          {
                              return date('d/m/Y', strtotime($date));
                          }
                          $sql = 'SELECT vacation_start, vacation_end, date_submitted, days_requested, status FROM applications JOIN users WHERE users.email = ? AND applications.employee_id=(SELECT id FROM users WHERE email = ?) ORDER BY date_submitted DESC';
                          if ($stmt = mysqli_prepare($link, $sql)) {
                              // Bind variables to the prepared statement as parameters
                              mysqli_stmt_bind_param($stmt, "ss", $_SESSION["email"], $_SESSION["email"]);

                              // Set parameters
                              $param_email = $_SESSION["email"];
                              // Attempt to execute the prepared statement
                              if (mysqli_stmt_execute($stmt)) {
                                  /* store result */
                                  mysqli_stmt_store_result($stmt);
                                  mysqli_stmt_bind_result($stmt, $vacation_start, $vacation_end, $date_submitted, $days_requested, $status);
                                  if (mysqli_stmt_num_rows($stmt) > 0) {
                                      echo '<table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>
                                          <th >Submission Date</th>
                                          <th >Requested Dates</th>
                                          <th >Requested Days</th>
                                          <th >Status</th>
                                        </tr>
                                      </thead>
                                      <tbody>';
                                      $applications = array();
                                      while (mysqli_stmt_fetch($stmt)) {
                                          array_push($applications, array("requested_dates" => format_date($vacation_start) . ' - ' . format_date($vacation_end), "submission_date" => format_date($date_submitted), "requested_days" => $days_requested, "status" => $status));
                                      }

                                      function colorize_status($stat)
                                      {
                                          switch ($stat) {
                                          case 'pending':
                                              return ucfirst($stat);
                                          case 'accepted':
                                              return '<font color="green">' . ucfirst($stat) . '</font>';
                                          case 'rejected':
                                              return '<font color="red">' . ucfirst($stat) . '</font>';
                                          default:
                                              return ucfirst($stat);
                                          }
                                      }
                                      foreach ($applications as $application) {
                                          echo '<tr>
                                                <th>'. $application['submission_date'] .'</th>
                                                <th>'. $application['requested_dates'] .'</th>
                                                <th>'. $application['requested_days'] .'</th>
                                                <th>'. colorize_status($application['status']) .'</th>
                                                </tr>';
                                      }
                                      echo '</tbody></table>';
                                  // Close statement
                                  } else {
                                      echo '<p>No applications found!</p>';
                                  }
                                  mysqli_stmt_close($stmt);
                              }
                          }
                          ?>
                          <p>
                              <div class="text-center"><a href="/pages/logout.php" class="btn btn-danger">Log out</a></div>
                          </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
