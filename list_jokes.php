<?php
/**
 * Script para consultar los chistes guardados en la base de datos SQLite
 */

$dbPath = __DIR__ . '/tmp/chuck_jokes.sqlite';

if (!file_exists($dbPath)) {
    echo "❌ La base de datos no existe en: $dbPath\n";
    exit(1);
}

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Contar total de chistes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM jokes");
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "🎭 Total de chistes guardados: $total\n\n";
    
    if ($total > 0) {
        // Obtener todos los chistes
        $stmt = $pdo->query("SELECT id, setup, created FROM jokes ORDER BY created DESC");
        $jokes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "📝 Lista de chistes:\n";
        echo str_repeat("-", 80) . "\n";
        
        foreach ($jokes as $index => $joke) {
            $created = date('d/m/Y H:i:s', strtotime($joke['created']));
            echo sprintf("#%d (ID: %d) - %s\n", $index + 1, $joke['id'], $created);
            echo "   " . wordwrap($joke['setup'], 70, "\n   ") . "\n\n";
        }
    } else {
        echo "No hay chistes guardados aún.\n";
        echo "Visita http://localhost:8765 para obtener y guardar chistes.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error al acceder a la base de datos: " . $e->getMessage() . "\n";
}
