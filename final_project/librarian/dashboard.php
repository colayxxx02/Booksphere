<?php 
session_start(); 
include '../db.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'librarian') { 
    header("Location: ../index.php"); 
    exit(); 
} 

// Queries
$total_books = $conn->query("SELECT COUNT(*) as c FROM books")->fetch_assoc()['c']; 
$available = $conn->query("SELECT COUNT(*) as c FROM books WHERE status='available'")->fetch_assoc()['c']; 
$borrowed = $conn->query("SELECT COUNT(*) as c FROM books WHERE status='borrowed'")->fetch_assoc()['c']; 
$damaged = $conn->query("SELECT COUNT(*) as c FROM books WHERE status='damaged'")->fetch_assoc()['c']; 
$overdue = $conn->query("SELECT COUNT(*) as c FROM transactions WHERE due_date < CURDATE() AND return_date IS NULL")->fetch_assoc()['c']; 
$total_members = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='member'")->fetch_assoc()['c']; 

$most_borrowed = $conn->query("SELECT b.title, b.author, b.category, COUNT(t.book_id) as borrow_count 
                               FROM transactions t JOIN books b ON t.book_id = b.book_id 
                               GROUP BY t.book_id ORDER BY borrow_count DESC LIMIT 5"); 

$top_books = []; 
while ($row = $most_borrowed->fetch_assoc()) { 
    $top_books[] = $row; 
} 
?>
