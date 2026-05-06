<?php
session_start();

if(isset($_SESSION['user'])) {
    echo json_encode(['connected' => true]);
} else {
    echo json_encode(['connected' => false]);
}
?>