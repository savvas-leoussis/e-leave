<?php

// Prints employee type in camel case with the corresponding color
// (employee: blue, supervisor:orange)
function colorize_type($typ)
{
    switch ($typ) {
  case 'employee':
      return '<font color="#246cb4">Employee</font>';
  case 'supervisor':
      return '<font color="ef883b">Admin</font>';
  default:
      return ucfirst($typ);
  }
}
