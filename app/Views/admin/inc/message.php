<?php

$session = new Core\Session;

if($session->has("success-message")) {
    $data = $session->flash('success-message');
   echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
         ".$data."
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
         </button>
        </div>";

} elseif ($session->has("error-message")) {
    $data = $session->flash('error-message');
    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
         ".$data."
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
         </button>
        </div>";
}

?>