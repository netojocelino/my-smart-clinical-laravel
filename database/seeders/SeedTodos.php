<?php

namespace Database\Seeders;

use App\Models\TodoItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeedTodos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title'        => 'Crie uma página que exiba uma lista de todas as tarefas cadastradas.',
                'description'  => 'A lista deve exibir o título, a descrição e o status (concluída ou não,) de cada tarefa.',
                'status'       => 'done',
                'completed_at' => date('Y-m-d H:i:s'),
            ],

            [
                'title'        => 'Implemente um formulário para adicionar novas tarefas à lista.',
                'description'  => 'Os campos obrigatórios devem incluir título e descrição da taref,a.',
                'status'       => 'done',
                'completed_at' => date('Y-m-d H:i:s'),
            ],

            [
                'title'        => 'Permita a atualização do status de uma tarefa (marcar como concluída ou não concluída).',
                'description'  => 'Essa funcionalidade pode ser implementada por meio de botões na lista ou em uma página se,parada.',
                'status'       => 'pendent',
                'completed_at' => null,
            ],

            [
                'title'        => 'O candidato deve utilizar o framework Laravel para desenvolver a aplicação.',
                'description'  => '',
                'status'       => 'done',
                'completed_at' => date('Y-m-d H:i:s'),
            ],


            [
                'title'        => 'Utilize um banco de dados para armazenar as tarefas.',
                'description'  => '',
                'status'       => 'pendent',
                'completed_at' => null,
            ],
            [
                'title'        => 'Crie uma migração para a tabela de "Todos".',
                'description'  => '',
                'status'       => 'done',
                'completed_at' => date('Y-m-d H:i:s'),
            ],


            [
                'title'        => 'Seja capaz de estabelecer relacionamentos adequados entre os modelos, se necessário.',
                'description'  => 'Validações:',
                'status'       => 'pendent',
                'completed_at' => null,
            ],

            [
                'title'        => 'Implemente validações no lado do servidor para garantir dados consistentes.',
                'description'  => '',
                'status'       => 'pendent',
                'completed_at' => null,
            ],


            [
                'title'        => 'Organize o código utilizando rotas e controllers de forma eficiente.',
                'description'  => '',
                'status'       => 'pendent',
                'completed_at' => null,
            ],


            [
                'title'        => 'Se desejar, o candidato pode adicionar um mínimo de estilo à aplicação para torná-la mais amigável ao usuário.',
                'description'  => '',
                'status'       => 'pendent',
                'completed_at' => null,
            ],


        ];

        foreach ($data as $item) {
            TodoItem::create([
                'title'        => data_get($item, 'title', ''),
                'description'  => data_get($item, 'description', ''),
                'status'       => data_get($item, 'status', 'pendent'),
                'completed_at' => data_get($item, 'completed_at'),
            ]);
        }
    }
}
