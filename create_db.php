<?php
// Script para crear la base de datos SQLite manualmente

try {
    $pdo = new PDO('sqlite:tmp/chuck_jokes.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Crear la tabla jokes
    $sql = "CREATE TABLE IF NOT EXISTS jokes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        setup VARCHAR(255) NOT NULL,
        punchline VARCHAR(255) NOT NULL,
        created DATETIME NOT NULL,
        modified DATETIME NOT NULL
    )";
    
    $pdo->exec($sql);
    
    // Crear tabla de migraciones para que CakePHP sepa que ya existe
    $sql2 = "CREATE TABLE IF NOT EXISTS phinxlog (
        version BIGINT NOT NULL,
        migration_name VARCHAR(100) DEFAULT NULL,
        start_time TIMESTAMP DEFAULT NULL,
        end_time TIMESTAMP DEFAULT NULL,
        breakpoint BOOLEAN NOT NULL DEFAULT FALSE,
        PRIMARY KEY (version)
    )";
    
    $pdo->exec($sql2);
    
    // Insertar el registro de migración
    $sql3 = "INSERT OR IGNORE INTO phinxlog (version, migration_name, start_time, end_time) 
             VALUES (20250924094735, 'CreateJokes', datetime('now'), datetime('now'))";
    
    $pdo->exec($sql3);
    
    echo "✅ Base de datos creada correctamente!\n";
    echo "✅ Tabla 'jokes' creada\n";
    echo "✅ Tabla 'phinxlog' creada\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
