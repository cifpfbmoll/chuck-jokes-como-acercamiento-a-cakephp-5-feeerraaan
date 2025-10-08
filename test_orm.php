<?php
require 'vendor/autoload.php';

use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

// Configurar CakePHP básico
Configure::write('debug', true);
Configure::write('App.namespace', 'App');

// Configurar conexión
ConnectionManager::setConfig('default', [
    'className' => 'Cake\Database\Connection',
    'driver' => 'Cake\Database\Driver\Sqlite',
    'database' => 'tmp/chuck_jokes.sqlite',
    'encoding' => 'utf8',
    'timezone' => 'UTC',
    'cacheMetadata' => false,
]);

try {
    echo "=== Probando ORM de CakePHP ===" . PHP_EOL;
    
    // Obtener conexión
    $connection = ConnectionManager::get('default');
    echo "✅ Conexión establecida" . PHP_EOL;
    
    // Probar query directo
    $result = $connection->execute('SELECT COUNT(*) as count FROM jokes');
    $count = $result->fetch()['count'];
    echo "✅ Query directo: {$count} registros en jokes" . PHP_EOL;
    
    // Probar esquema
    $schema = $connection->getSchemaCollection();
    $tableSchema = $schema->describe('jokes');
    echo "✅ Esquema obtenido: " . count($tableSchema->columns()) . " columnas" . PHP_EOL;
    foreach ($tableSchema->columns() as $column) {
        $columnSchema = $tableSchema->getColumn($column);
        echo "  - {$column}: {$columnSchema['type']}" . PHP_EOL;
    }
    
    // Probar TableRegistry
    $jokesTable = TableRegistry::getTableLocator()->get('Jokes');
    echo "✅ Tabla cargada via TableRegistry" . PHP_EOL;
    
    $count = $jokesTable->find()->count();
    echo "✅ Conteo via ORM: {$count} registros" . PHP_EOL;
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
    echo "Trace: " . $e->getTraceAsString() . PHP_EOL;
}
?>
