<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Hash;
use Cake\Utility\Text;
use \Cake\Database\Expression\QueryExpression;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotAcceptableException;
use Cake\Error\Debugger;

class AuthKeysController extends AppController
{
    public $filterFields = ['Users.username', 'authkey', 'comment', 'Users.id'];
    public $quickFilterFields = ['authkey', ['comment' => true]];
    public $containFields = ['Users'];

    public function index()
    {
        $this->CRUD->index([
            'filters' => $this->filterFields,
            'quickFilters' => $this->quickFilterFields,
            'contain' => $this->containFields,
            'exclude_fields' => ['authkey']
        ]);
        $responsePayload = $this->CRUD->getResponsePayload();
        if (!empty($responsePayload)) {
            return $responsePayload;
        }
        $this->set('metaGroup', $this->isAdmin ? 'Administration' : 'Cerebrate');
    }

    public function delete($id)
    {
        $this->CRUD->delete($id);
        $responsePayload = $this->CRUD->getResponsePayload();
        if (!empty($responsePayload)) {
            return $responsePayload;
        }
        $this->set('metaGroup', $this->isAdmin ? 'Administration' : 'Cerebrate');
    }

    public function add()
    {
        $this->set('metaGroup', $this->isAdmin ? 'Administration' : 'Cerebrate');
        $this->CRUD->add([
            'displayOnSuccess' => 'authkey_display'
        ]);
        $responsePayload = $this->CRUD->getResponsePayload([
            'displayOnSuccess' => 'authkey_display'
        ]);
        if (!empty($responsePayload)) {
            return $responsePayload;
        }
        $this->loadModel('Users');
        $dropdownData = [
            'user' => $this->Users->find('list', [
                'sort' => ['username' => 'asc']
            ])
        ];
        $this->set(compact('dropdownData'));
    }
}
