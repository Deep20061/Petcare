<?php
session_start();
$host = "aws-0-eu-west-3.pooler.supabase.com";
$port = "5432"; // Porta padrão do PostgreSQL
$dbname = "postgres";
$user = "postgres.kszhqvvmlrlkvsvbpinx";
$password = "LEVufRUwFPTdywIp";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die("Could not connect to the database.");
?>
