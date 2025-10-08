<?php
require 'vendor/autoload.php';

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure\Engine\PhpConfig;

// Cargar configuración
Configure::config('default', new PhpConfig());
Configure::load('app', 'default', false);
Configure::load('app_local', 'default');

try {
    $connection = ConnectionManager::get('default');
    $tables = $connection->getSchemaCollection()->listTables();
    echo "✅ Conexión exitosa a la base de datos\n";
    echo "Tablas encontradas: " . implode(', ', $tables) . "\n";
    
    // Verificar si existe la tabla jokes
    if (in_array('jokes', $tables)) {
        echo "✅ Tabla 'jokes' encontrada\n";
        
        // Contar registros
        $query = $connection->execute('SELECT COUNT(*) as count FROM jokes');
        $result = $query->fetch();
        echo "Número de chistes en la base de datos: " . $result['count'] . "\n";
    } else {
        echo "❌ Tabla 'jokes' no encontrada\n";
    }
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
}
