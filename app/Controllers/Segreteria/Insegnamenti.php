<?php

namespace App\Controllers\Segreteria;


use App\Controllers\BaseController;
use App\Repositories\InsegnamentiData;
use App\Repositories\CdlData;
use Exception;

class Insegnamenti extends BaseController
{
    public function listInsegnamenti()
    {
        helper('form');


        $data['allCdl'] = CdlData::getAllCdl();
        if (!$this->request->is('post')) {
            $data['insegnamenti'] = InsegnamentiData::getInsegnamenti();
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/insegnamenti/listinsegnamenti', $data)
                . view('templates/footer');
        }
        $post = $this->request->getPost(['id_cdl']);

        $data['insegnamenti'] = InsegnamentiData::getInsegnamentiByCdl($post['id_cdl']);
        return view('templates/header', ['title' => 'Segreteria'])
            . view("segreteria/insegnamenti/listinsegnamenti", $data)
            . view('templates/footer');
    }

}