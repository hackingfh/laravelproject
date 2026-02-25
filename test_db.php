<?php
try {
    $db = new PDO('sqlite:database/database.sqlite');
    echo "Connection successful!\n";
    $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Tables in database:\n";
    foreach ($tables as $table) {
        echo "- " . $table['name'] . "\n";
    }
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
