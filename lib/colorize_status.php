<?php
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
