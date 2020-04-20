<?php function format_date($date)

// Converts date string to a date string of the format: d/m/Y
{
    return date('d/m/Y', strtotime($date));
}
