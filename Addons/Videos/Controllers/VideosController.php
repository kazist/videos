<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Videos\Addons\Videos\Controllers;

use Kazist\Controller\AddonController;
use Videos\Addons\Videos\Models\VideosModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class VideosController extends AddonController {

    function indexAction($offset = 0, $limit = 6) {

        $model = new VideosModel;

        $videos = $model->getVideos($limit);

        $data_arr['videos'] = $videos;

        $this->html = $this->render('Videos:Addons:Videos:views:videos.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

}
