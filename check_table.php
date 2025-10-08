<?php
// Crear la base de datos SQLite desde cero
try {
    echo "=== Creando base de datos SQLite desde cero ===" . PHP_EOL;
    
    $pdo = new PDO('sqlite:tmp/chuck_jokes.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conectado a SQLite" . PHP_EOL;
    
    // Crear la tabla jokes
    $sql = "CREATE TABLE jokes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        setup TEXT NOT NULL,
        punchline TEXT NOT NULL,
        created DATETIME NOT NULL,
        modified DATETIME NOT NULL
    )";
    
    $pdo->exec($sql);
    echo "✅ Tabla 'jokes' creada correctamente" . PHP_EOL;
    
    // Crear tabla de migraciones (para que CakePHP sepa que está actualizada)
    $sql2 = "CREATE TABLE phinxlog (
        version BIGINT NOT NULL,
        migration_name VARCHAR(100) DEFAULT NULL,
        start_time TIMESTAMP DEFAULT NULL,
        end_time TIMESTAMP DEFAULT NULL,
        breakpoint BOOLEAN NOT NULL DEFAULT 0,
        PRIMARY KEY (version)
    )";
    
    $pdo->exec($sql2);
    echo "✅ Tabla 'phinxlog' creada correctamente" . PHP_EOL;
    
    // Insertar el registro de migración
    $sql3 = "INSERT INTO phinxlog (version, migration_name, start_time, end_time) 
             VALUES (20250924094735, 'CreateJokes', datetime('now'), datetime('now'))";
    
    $pdo->exec($sql3);
    echo "✅ Registro de migración insertado" . PHP_EOL;
    
    // Verificar que todo está bien
    $result = $pdo->query('PRAGMA table_info(jokes)');
    echo "📋 Estructura final de la tabla jokes:" . PHP_EOL;
    foreach ($result as $row) {
        echo "  - " . $row['name'] . " (" . $row['type'] . ")" . PHP_EOL;
    }
    
    echo "🎉 ¡Base de datos creada exitosamente!" . PHP_EOL;
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
?>
