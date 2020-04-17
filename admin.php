<?php
require_once "pages/config.php";
include './lib/colorize_type.php';
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    header("location: pages/admin_login.php");
    exit;
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>E-Leave - Admin Dashboard</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/css/style.css">
</head>

<body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
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
                            <h1 style="font-size: 48px; font-weight: 400; margin: 2;">E-Leave Admin Dashboard</h1> <img src=" https://www.epignosishq.com/wp-content/themes/epignosishq/dist/images/logo.svg" width="220" height="120" style="margin-top:30px ;display: block; border: 0px;" />
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
                                $sql = "SELECT first_name, last_name FROM users WHERE email = ?";
                                if ($stmt = mysqli_prepare($link, $sql)) {
                                    // Bind variables to the prepared statement as parameters
                                    mysqli_stmt_bind_param($stmt, "s", $_SESSION["admin_email"]);

                                    // Set parameters
                                    $param_email = $_SESSION["admin_email"];
                                    // Attempt to execute the prepared statement
                                    if (mysqli_stmt_execute($stmt)) {
                                        /* store result */
                                        mysqli_stmt_bind_result($stmt, $first_name, $last_name);

                                        while (mysqli_stmt_fetch($stmt)) {
                                            printf("%s %s", ucfirst($first_name), ucfirst($last_name));
                                        }

                                        // Close statement
                                        mysqli_stmt_close($stmt);
                                    }
                                }
                                ?>.
                                  <div class="text-center"><a style="margin: 10px;" href="/pages/admin_create_user.php" class="btn btn-primary">Create User</a></div>
                          <?php
                          $sql = 'SELECT id, first_name, last_name, email, type FROM users ORDER BY type DESC';
                          if ($stmt = mysqli_prepare($link, $sql)) {

                              // Set parameters
                              $param_email = $_SESSION["admin_email"];
                              // Attempt to execute the prepared statement
                              if (mysqli_stmt_execute($stmt)) {
                                  /* store result */
                                  mysqli_stmt_store_result($stmt);
                                  mysqli_stmt_bind_result($stmt, $id, $first_name, $last_name, $email, $type);
                                  if (mysqli_stmt_num_rows($stmt) > 0) {
                                      echo '<table class="table table-striped table-hover table-bordered table-sm" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>
                                          <th>First Name</th>
                                          <th>Last Name</th>
                                          <th>E-Mail</th>
                                          <th>Type</th>
                                        </tr>
                                      </thead>
                                      <tbody>';
                                      $users = array();
                                      while (mysqli_stmt_fetch($stmt)) {
                                          array_push($users, array("id" => $id, "first_name" => $first_name, "last_name" => $last_name, "email" => $email, "type" => $type));
                                      }
                                      foreach ($users as $user) {
                                          echo "<tr class='clickable-row' onclick='window.location=\"http://localhost/pages/admin_create_user.php?user_id=" . $user['id'] . "\";'>
                                                <th>" . $user['first_name'] . "</th>
                                                <th>" . $user['last_name'] . "</th>
                                                <th>" . $user['email'] . "</th>
                                                <th>" . colorize_type($user['type']) . "</th>
                                                </tr>";
                                      }
                                      echo '</tbody></table>';
                                  // Close statement
                                  } else {
                                      echo '<p>No users found!</p>';
                                  }
                                  mysqli_stmt_close($stmt);
                              }
                          }
                          ?>
                          <p>
                              <div class="text-center"><a href="/pages/admin_logout.php" class="btn btn-danger">Log out</a></div>
                          </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
