<?php
// Verificar base de datos
try {
    $pdo = new PDO('sqlite:tmp/chuck_jokes.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Verificando base de datos ===" . PHP_EOL;
    
    // Verificar tablas
    $result = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
    echo "📋 Tablas existentes:" . PHP_EOL;
    foreach ($result as $row) {
        echo "  - " . $row['name'] . PHP_EOL;
    }
    
    // Verificar estructura de jokes
    $tableCheck = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='jokes'")->fetch();
    if ($tableCheck) {
        echo "📊 Estructura de la tabla jokes:" . PHP_EOL;
        $result = $pdo->query('PRAGMA table_info(jokes)');
        foreach ($result as $row) {
            echo "  - " . $row['name'] . " (" . $row['type'] . ")" . PHP_EOL;
        }
        
        // Contar registros
        $result = $pdo->query('SELECT COUNT(*) as count FROM jokes');
        $count = $result->fetch()['count'];
        echo "📈 Registros: " . $count . PHP_EOL;
    } else {
        echo "❌ La tabla 'jokes' no existe!" . PHP_EOL;
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
}
?>
