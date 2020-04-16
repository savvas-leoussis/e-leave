<?php
// Initialize the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$date_from = $date_to = $reason = "";
$date_from_err = $date_to_err = $reason_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate new password
    if (empty($_POST["date_from"]) or empty($_POST["date_to"])) {
        if (empty($_POST["date_from"])) {
            $date_from_err = "Please enter a start date.";
        }
        if (empty($_POST["date_to"])) {
            $date_to_err = "Please enter an end date.";
        }
    } elseif (strtotime($_POST["date_from"]) > strtotime($_POST["date_to"])) {
        $date_from_err = "Start date must be prior to end date.";
        $date_to_err = "End date must be after the start date.";
    } else {
        $date_from = $_POST["date_from"];
        $date_to = $_POST["date_to"];
    }

    if (empty($_POST["reason"])) {
        $reason_err = "Please enter a reason.";
    } else {
        $reason = $_POST["reason"] ;
    }
    function getWorkdays($date1, $date2, $workSat = false, $patron = null)
    {
        if (!defined('SATURDAY')) {
            define('SATURDAY', 6);
        }
        if (!defined('SUNDAY')) {
            define('SUNDAY', 0);
        }

        // Array of all public festivities
        $publicHolidays = array('01-01', '01-06', '04-25', '05-01', '06-02', '08-15', '11-01', '12-08', '12-25', '12-26');
        // The Patron day (if any) is added to public festivities
        if ($patron) {
            $publicHolidays[] = $patron;
        }

        /*
         * Array of all Easter Mondays in the given interval
         */
        $yearStart = date('Y', strtotime($date1));
        $yearEnd   = date('Y', strtotime($date2));

        for ($i = $yearStart; $i <= $yearEnd; $i++) {
            $easter = date('Y-m-d', easter_date($i));
            list($y, $m, $g) = explode("-", $easter);
            $monday = mktime(0, 0, 0, date($m), date($g)+1, date($y));
            $easterMondays[] = $monday;
        }

        $start = strtotime($date1);
        $end   = strtotime($date2);
        $workdays = 0;
        for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
            $day = date("w", $i);  // 0=sun, 1=mon, ..., 6=sat
            $mmgg = date('m-d', $i);
            if ($day != SUNDAY &&
          !in_array($mmgg, $publicHolidays) &&
          !in_array($i, $easterMondays) &&
          !($day == SATURDAY && $workSat == false)) {
                $workdays++;
            }
        }

        return intval($workdays);
    }
    function format_date($date)
    {
        return date('d/m/Y', strtotime($date));
    }
    $sql = "INSERT INTO applications (employee_id, vacation_start, vacation_end, reason, date_submitted, days_requested, status) VALUES ((SELECT id from users WHERE email = ?), ?, ?, ?, ? , ?, ?);";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        $curr_date = date("Y-m-d");
        $workdays = getWorkdays($date_from, $date_to);
        $status = 'pending';
        mysqli_stmt_bind_param($stmt, "sssssis", $_SESSION["email"], $date_from, $date_to, $reason, $curr_date, $workdays, $status);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {

            // Close statement
            mysqli_stmt_close($stmt);

            // Get application id
            $application_id = mysqli_insert_id($link);

            $sql2 = 'SELECT email FROM users WHERE id = (SELECT supervisor_id FROM users WHERE email = ?)';
            if ($stmt2 = mysqli_prepare($link, $sql2)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt2, "s", $_SESSION["email"]);

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt2)) {
                    /* store result */
                    mysqli_stmt_bind_result($stmt2, $supervisor_email);

                    while (mysqli_stmt_fetch($stmt2)) {
                        $email_to = $supervisor_email;
                    }
                    // Close statement

                    mysqli_stmt_close($stmt2);
                }
            }

            $sql3 = 'SELECT first_name, last_name FROM users WHERE email = ?';
            if ($stmt3 = mysqli_prepare($link, $sql3)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt3, "s", $_SESSION["email"]);

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt3)) {
                    /* store result */
                    mysqli_stmt_bind_result($stmt3, $first_name, $last_name);
                    while (mysqli_stmt_fetch($stmt3)) {
                        $f_name = $first_name;
                        $l_name = $last_name;
                    }
                    // Close statement

                    mysqli_stmt_close($stmt3);
                }
            }

            $to = $email_to;
            $subject = 'e-Leave Request';

            $accept_link = 'http://localhost/pages/handle_request.php?app_id=' . $application_id . '&accept=true&f_name=' . $f_name . '&l_name=' . $l_name;
            $reject_link = 'http://localhost/pages/handle_request.php?app_id=' . $application_id . '&accept=false&f_name=' . $f_name . '&l_name=' . $l_name;

            $message = file_get_contents('../templates/confirm.html', true);

            $message = str_replace("{user}", $f_name . ' ' . $l_name, $message);
            $message = str_replace("{vacation_start}", format_date($date_from), $message);
            $message = str_replace("{vacation_end}", format_date($date_to), $message);
            $message = str_replace("{reason}", $reason, $message);
            $message = str_replace("{accept_link}", $accept_link, $message);
            $message = str_replace("{reject_link}", $reject_link, $message);
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
            $headers .= 'From: e-leave@epignosis.com' . "\r\n";
            mail($email_to, $subject, $message, $headers);
            header("location: ../index.php");
            exit();
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>E-Leave - Submit Request</title>
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
                            <h1 style="font-size: 48px; font-weight: 400; margin: 2;">Sumbit Request</h1> <img src=" https://www.epignosishq.com/wp-content/themes/epignosishq/dist/images/logo.svg" width="220" height="120" style="margin-top:30px ;display: block; border: 0px;" />
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
                          <p>Please fill out this form to submit your leave request.</p>
                          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                              <div class="form-group <?php echo (!empty($date_from_err)) ? 'has-error' : ''; ?>">
                                  <label>Date From</label>
                                  <input type="date" min='1970-01-01' max='2037-12-31' name="date_from" class="form-control" value="<?php echo $new_password; ?>">
                                  <span class="help-block"><?php echo $date_from_err; ?></span>
                              </div>
                              <div class="form-group <?php echo (!empty($date_to_err)) ? 'has-error' : ''; ?>">
                                  <label>Date To</label>
                                  <input type="date" min='1970-01-01' max='2037-12-31' name="date_to" class="form-control">
                                  <span class="help-block"><?php echo $date_to_err; ?></span>
                              </div>
                              <div class="form-group <?php echo (!empty($reason_err)) ? 'has-error' : ''; ?>">
                                  <label>Reason</label>
                                  <textarea name="reason" class="form-control" rows="3"></textarea>
                                  <span class="help-block"><?php echo $reason_err; ?></span>
                              </div>
                              <div class="form-group">
                                  <input type="submit" class="btn btn-primary" value="Submit">
                                  <a class="btn btn-link" href="http://localhost/index.php">Cancel</a>
                              </div>
                          </form>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="mystyle.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>

</body>
</html>
