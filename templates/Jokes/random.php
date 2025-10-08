<?php
/** @var \App\View\AppView $this */
/** @var string $jokeText */
/** @var \App\Model\Entity\Joke $joke */
?>
<div class="jokes random">
    <h1>Chuck Norris - Chiste aleatorio</h1>
    
    <div class="navigation-links">
        <?= $this->Html->link('📝 Ver todos los chistes guardados', ['action' => 'index'], ['class' => 'button secondary']) ?>
        <?= $this->Html->link('🔄 Nuevo chiste', ['action' => 'random'], ['class' => 'button secondary']) ?>
    </div>
    
    <div id="joke-container">
        <blockquote id="joke-text"><?= h($jokeText) ?></blockquote>
    </div>

    <div id="save-section">
        <?= $this->Form->create($joke, ['id' => 'joke-form']) ?>
        <?= $this->Form->hidden('setup', ['value' => $jokeText, 'id' => 'joke-setup']) ?>
        <?= $this->Form->hidden('punchline', ['value' => '']) ?>
        <?= $this->Form->button('💾 Guardar en la base de datos', [
            'class' => 'button primary', 
            'id' => 'save-btn',
            'type' => 'button'
        ]) ?>
        <?= $this->Form->end() ?>
    </div>
    
    <div id="message" style="display: none; margin-top: 15px; padding: 10px; border-radius: 4px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const saveBtn = document.getElementById('save-btn');
    const messageDiv = document.getElementById('message');
    const jokeForm = document.getElementById('joke-form');
    
    saveBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Cambiar estado del botón
        saveBtn.disabled = true;
        saveBtn.textContent = '⏳ Guardando...';
        
        // Preparar datos del formulario
        const formData = new FormData(jokeForm);
        
        // Enviar vía fetch (más rápido que recargar la página)
        fetch('/jokes/save', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Mostrar mensaje
            messageDiv.style.display = 'block';
            if (data.success) {
                messageDiv.className = 'success-message';
                messageDiv.textContent = '✅ ' + data.message;
                // Deshabilitar el botón para evitar guardados duplicados
                saveBtn.textContent = '✅ Guardado';
                saveBtn.style.backgroundColor = '#28a745';
            } else {
                messageDiv.className = 'error-message';
                messageDiv.textContent = '❌ ' + data.message;
                // Restaurar botón para reintentar
                saveBtn.disabled = false;
                saveBtn.textContent = '💾 Guardar en la base de datos';
            }
        })
        .catch(error => {
            // Error de red o similar
            messageDiv.style.display = 'block';
            messageDiv.className = 'error-message';
            messageDiv.textContent = '❌ Error de conexión: ' + error.message;
            saveBtn.disabled = false;
            saveBtn.textContent = '💾 Guardar en la base de datos';
        });
    });
});
</script>

<style>
.navigation-links {
    margin-bottom: 20px;
}
.navigation-links .button {
    margin-right: 10px;
}
.button {
    display: inline-block;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    font-size: 14px;
}
.button.primary {
    background-color: #28a745;
    color: white;
}
.button.primary:hover {
    background-color: #218838;
}
.button.secondary {
    background-color: #6c757d;
    color: white;
}
.button.secondary:hover {
    background-color: #545b62;
    color: white;
    text-decoration: none;
}
blockquote {
    background-color: #f8f9fa;
    border-left: 4px solid #007bff;
    padding: 15px;
    margin: 20px 0;
    font-style: italic;
    font-size: 16px;
}
.success-message {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
    padding: 10px;
    border-radius: 4px;
    margin-top: 10px;
}
.error-message {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
    padding: 10px;
    border-radius: 4px;
    margin-top: 10px;
}
</style>


