<?php
declare(strict_types=1);

namespace App\Controller\Admin;

class AllowanceRatesController extends AppAdminController
{
    public function index()
    {
        $this->loadModel('AllowanceRates');
        $this->AllowanceRates->belongsTo('JobLevels');

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $draw = (int)$this->request->getQuery('draw', 1);
            $start = (int)$this->request->getQuery('start', 0);
            $length = (int)$this->request->getQuery('length', 10);
            $searchValue = $this->request->getQuery('search')['value'] ?? '';
            $orderColumn = $this->request->getQuery('order')[0]['column'] ?? 0;
            $orderDir = $this->request->getQuery('order')[0]['dir'] ?? 'asc';

            $columns = [
                0 => 'JobLevels.level_code',
                1 => 'JobLevels.level_name',
                2 => 'AllowanceRates.travel_type',
                3 => 'AllowanceRates.accommodation_rate',
                4 => 'AllowanceRates.per_diem_rate',
                5 => 'AllowanceRates.transport_rate',
                6 => 'AllowanceRates.currency',
                7 => 'AllowanceRates.created',
            ];
            $orderField = $columns[$orderColumn] ?? 'JobLevels.level_code';

            $query = $this->AllowanceRates->find()->contain(['JobLevels']);
            if (!empty($searchValue)) {
                $query->where([
                    'OR' => [
                        'JobLevels.level_code LIKE' => "%$searchValue%",
                        'JobLevels.level_name LIKE' => "%$searchValue%",
                        'AllowanceRates.travel_type LIKE' => "%$searchValue%",
                        'AllowanceRates.currency LIKE' => "%$searchValue%",
                    ]
                ]);
            }

            $recordsTotal = $this->AllowanceRates->find()->count();
            $recordsFiltered = $query->count();
            $rows = $query->order([$orderField => $orderDir])
                ->limit($length)
                ->offset($start)
                ->all();

            $data = [];
            foreach ($rows as $r) {
                $data[] = [
                    'id' => $r->id,
                    'job_level' => $r->job_level->level_name ?? ($r->job_level->level_code ?? ''),
                    'job_level_code' => $r->job_level->level_code ?? '',
                    'travel_type' => $r->travel_type,
                    'accommodation_rate' => $r->accommodation_rate,
                    'per_diem_rate' => $r->per_diem_rate ?? $r->feeding_rate,
                    'transport_rate' => $r->transport_rate,
                    'currency' => $r->currency,
                    'created' => $r->created ? $r->created->format('Y-m-d H:i:s') : null,
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

        $query = $this->AllowanceRates->find()->contain(['JobLevels'])->order(['JobLevels.level_code' => 'ASC', 'travel_type' => 'ASC']);
        $rates = $this->paginate($query, ['limit' => 20]);
        $this->set(compact('rates'));
    }

    public function add()
    {
        $this->loadModel('AllowanceRates');
        $this->loadModel('JobLevels');
        $rate = $this->AllowanceRates->newEmptyEntity();
        if ($this->request->is('post')) {
            $rate = $this->AllowanceRates->patchEntity($rate, $this->request->getData());
            if ($this->AllowanceRates->save($rate)) {
                $this->Flash->success('Allowance rate created.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Unable to create allowance rate.');
        }
        $jobLevels = $this->JobLevels->find('list', ['keyField' => 'id', 'valueField' => 'level_name'])->order(['level_code' => 'ASC'])->toArray();
        $this->set(compact('rate', 'jobLevels'));
    }

    public function edit($id)
    {
        $this->loadModel('AllowanceRates');
        $this->loadModel('JobLevels');
        $rate = $this->AllowanceRates->get($id);
        if ($this->request->is(['post','put','patch'])) {
            $rate = $this->AllowanceRates->patchEntity($rate, $this->request->getData());
            if ($this->AllowanceRates->save($rate)) {
                $this->Flash->success('Allowance rate updated.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Unable to update allowance rate.');
        }
        $jobLevels = $this->JobLevels->find('list', ['keyField' => 'id', 'valueField' => 'level_name'])->order(['level_code' => 'ASC'])->toArray();
        $this->set(compact('rate', 'jobLevels'));
    }

    public function delete($id)
    {
        $this->request->allowMethod(['post','delete']);
        $this->loadModel('AllowanceRates');
        $rate = $this->AllowanceRates->get($id);
        if ($this->AllowanceRates->delete($rate)) {
            $this->Flash->success('Allowance rate deleted.');
        } else {
            $this->Flash->error('Unable to delete allowance rate.');
        }
        return $this->redirect(['action' => 'index']);
    }
}
