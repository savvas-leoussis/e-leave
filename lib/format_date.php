<?php function format_date($date)
{
    return date('d/m/Y', strtotime($date));
}
