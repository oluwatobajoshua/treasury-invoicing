<?php
declare(strict_types=1);

namespace App\Controller\Admin;

class JobLevelsController extends AppAdminController
{
    public function index()
    {
        $this->loadModel('JobLevels');

        if ($this->request->is('ajax')) {
            $this->autoRender = false;

            $draw = (int)$this->request->getQuery('draw', 1);
            $start = (int)$this->request->getQuery('start', 0);
            $length = (int)$this->request->getQuery('length', 10);
            $searchValue = $this->request->getQuery('search')['value'] ?? '';
            $orderColumn = $this->request->getQuery('order')[0]['column'] ?? 1; // default by code
            $orderDir = $this->request->getQuery('order')[0]['dir'] ?? 'asc';

            $columns = [
                0 => 'JobLevels.id',
                1 => 'JobLevels.level_code',
                2 => 'JobLevels.level_name',
                3 => 'JobLevels.description',
                4 => 'JobLevels.created',
            ];
            $orderField = $columns[$orderColumn] ?? 'JobLevels.level_code';

            $query = $this->JobLevels->find();
            if (!empty($searchValue)) {
                $query->where([
                    'OR' => [
                        'JobLevels.level_code LIKE' => "%$searchValue%",
                        'JobLevels.level_name LIKE' => "%$searchValue%",
                        'JobLevels.description LIKE' => "%$searchValue%",
                    ]
                ]);
            }

            $recordsTotal = $this->JobLevels->find()->count();
            $recordsFiltered = $query->count();

            $rows = $query->order([$orderField => $orderDir])
                ->limit($length)
                ->offset($start)
                ->all();

            $data = [];
            foreach ($rows as $l) {
                $data[] = [
                    'id' => $l->id,
                    'level_code' => $l->level_code,
                    'level_name' => $l->level_name,
                    'description' => $l->description,
                    'created' => $l->created ? $l->created->format('Y-m-d H:i:s') : null,
                ];
            }

            $response = [
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ];
            return $this->response->withType('application/json')->withStringBody(json_encode($response));
        }

        // fallback
        $levels = $this->paginate($this->JobLevels->find()->order(['level_code' => 'ASC']), ['limit' => 20]);
        $this->set(compact('levels'));
    }

    public function add()
    {
        $this->loadModel('JobLevels');
        $level = $this->JobLevels->newEmptyEntity();
        if ($this->request->is('post')) {
            $level = $this->JobLevels->patchEntity($level, $this->request->getData());
            if ($this->JobLevels->save($level)) {
                $this->Flash->success('Job level created.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Unable to create job level.');
        }
        $this->set(compact('level'));
    }

    public function edit($id)
    {
        $this->loadModel('JobLevels');
        $level = $this->JobLevels->get($id);
        if ($this->request->is(['post','put','patch'])) {
            $level = $this->JobLevels->patchEntity($level, $this->request->getData());
            if ($this->JobLevels->save($level)) {
                $this->Flash->success('Job level updated.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Unable to update job level.');
        }
        $this->set(compact('level'));
    }

    public function delete($id)
    {
        $this->request->allowMethod(['post', 'delete']);
        $this->loadModel('JobLevels');
        $level = $this->JobLevels->get($id);
        if ($this->JobLevels->delete($level)) {
            $this->Flash->success('Job level deleted.');
        } else {
            $this->Flash->error('Unable to delete job level.');
        }
        return $this->redirect(['action' => 'index']);
    }
}
