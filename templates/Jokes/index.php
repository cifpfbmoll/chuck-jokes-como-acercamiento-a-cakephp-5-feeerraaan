<?php
/**
 * @var \App\View\AppView $this
 * @var array $jokes
 */
?>
<div class="jokes index content">
    <h3><?= __('Chistes Guardados') ?></h3>
    
    <div class="mb-3">
        <?= $this->Html->link(__('Obtener Nuevo Chiste'), ['action' => 'random'], ['class' => 'button']) ?>
    </div>

    <?php if (empty($jokes)): ?>
        <div class="alert alert-info">
            <p>No hay chistes guardados aún.</p>
            <p><?= $this->Html->link('¡Obtén tu primer chiste!', ['action' => 'random']) ?></p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th><?= __('ID') ?></th>
                        <th><?= __('Chiste') ?></th>
                        <th><?= __('Fecha') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jokes as $joke): ?>
                    <tr>
                        <td><?= $this->Number->format($joke->id) ?></td>
                        <td class="joke-text"><?= h($joke->setup) ?></td>
                        <td><?= h($joke->created->format('d/m/Y H:i:s')) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="paginator">
            <p>Total de chistes guardados: <strong><?= count($jokes) ?></strong></p>
        </div>
    <?php endif; ?>
</div>

<style>
.joke-text {
    max-width: 400px;
    word-wrap: break-word;
}
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}
.alert-info {
    color: #31708f;
    background-color: #d9edf7;
    border-color: #bce8f1;
}
.button {
    display: inline-block;
    padding: 8px 16px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    border: none;
    cursor: pointer;
}
.button:hover {
    background-color: #0056b3;
    color: white;
    text-decoration: none;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
    border-collapse: collapse;
}
.table th,
.table td {
    padding: 8px;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
    background-color: #f8f9fa;
}
</style>
