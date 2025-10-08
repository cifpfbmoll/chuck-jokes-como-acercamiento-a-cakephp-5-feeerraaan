<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Client;
use Cake\Http\Exception\BadRequestException;

class JokesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('default');
        $this->request->allowMethod(['get', 'post']);
    }

    public function random()
    {
        $jokesTable = $this->fetchTable('Jokes');
        $jokeText = '';
        
        // Solo obtener chiste de la API si no es POST (no estamos guardando)
        if (!$this->request->is('post')) {
            $jokeText = $this->getRandomJoke();
        }

        if ($this->request->is('post')) {
            // Procesar el guardado rápidamente
            $data = $this->request->getData();
            $jokeData = [
                'setup' => mb_substr((string)($data['setup'] ?? ''), 0, 255),
                'punchline' => mb_substr((string)($data['punchline'] ?? ''), 0, 255)
            ];
            
            $joke = $jokesTable->newEntity($jokeData);
            
            if ($jokesTable->save($joke)) {
                $this->Flash->success('¡Chiste guardado exitosamente!');
                return $this->redirect(['action' => 'random']);
            } else {
                $this->Flash->error('Error al guardar el chiste: ' . json_encode($joke->getErrors()));
                // Si falla, obtener nuevo chiste para mostrar
                $jokeText = $this->getRandomJoke();
            }
        }

        // Crear entidad vacía para el formulario
        $joke = $jokesTable->newEmptyEntity();
        $this->set(compact('jokeText', 'joke'));
    }
    
    /**
     * Obtiene un chiste aleatorio de la API
     */
    private function getRandomJoke(): string
    {
        try {
            $client = new Client(['timeout' => 5]); // Timeout de 5 segundos
            $response = $client->get('https://api.chucknorris.io/jokes/random');
            
            if (!$response->isOk()) {
                return 'Chuck Norris no necesita chistes, los chistes lo necesitan a él.';
            }
            
            $data = $response->getJson();
            return (string)($data['value'] ?? 'Error obteniendo chiste');
            
        } catch (\Exception $e) {
            // Chiste de respaldo si falla la API
            return 'Chuck Norris puede dividir por cero... y el resultado siempre es Chuck Norris.';
        }
    }

    /**
     * Muestra todos los chistes guardados en la base de datos
     */
    public function index()
    {
        $jokesTable = $this->fetchTable('Jokes');
        $jokes = $jokesTable->find('all')
            ->orderBy(['created' => 'DESC'])
            ->toArray();
        
        $this->set(compact('jokes'));
    }
    
    /**
     * Guarda un chiste vía AJAX (más rápido)
     */
    public function save()
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;
        
        try {
            $data = $this->request->getData();
            $jokesTable = $this->fetchTable('Jokes');
            
            $jokeData = [
                'setup' => mb_substr((string)($data['setup'] ?? ''), 0, 255),
                'punchline' => mb_substr((string)($data['punchline'] ?? ''), 0, 255)
            ];
            
            $joke = $jokesTable->newEntity($jokeData);
            
            if ($jokesTable->save($joke)) {
                $response = [
                    'success' => true,
                    'message' => '¡Chiste guardado exitosamente!',
                    'id' => $joke->id
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Error al guardar: ' . json_encode($joke->getErrors())
                ];
            }
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage()
            ];
        }
        
        $this->response = $this->response->withType('application/json');
        $this->response->getBody()->write(json_encode($response));
        return $this->response;
    }
}


