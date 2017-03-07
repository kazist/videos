<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of PlaylistsController
 *
 * @author sbc
 */

namespace Videos\Playlists\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Videos\Playlists\Code\Models\PlaylistsModel;

class PlaylistsController extends BaseController {

    public function indexAction() {

        $this->model = new PlaylistsModel;

        $playlists = $this->model->getRecords($offset, $limit);

        foreach ($playlists as $key => $playlist) {
            $playlists[$key]->videos = $this->model->getPlaylistVideosList($playlist->id, 10);
        }

        $data_arr['show_messages'] = true;
        $data_arr['total'] = $this->model->getTotal();
        $data_arr['playlists'] = $playlists;

        $this->html = $this->render('Videos:Playlists:Code:views:table.index.twig', $data_arr);

        $response = $this->response($this->html);


        return $response;
    }

    public function detailAction() {

        $this->model = new PlaylistsModel;

        $id = $this->request->get('id');
        $video_id = $this->request->get('video_id');

        $item = $this->model->getRecord($id);

        $item->videos = $this->model->getPlaylistVideosList($item->id);
        $video = $this->model->getVideo($video_id);

        $data_arr['show_messages'] = true;
        $data_arr['total'] = $this->model->getTotal();
        $data_arr['item'] = $item;
        $data_arr['video'] = $video;

        $this->html = $this->render('Videos:Playlists:Code:views:detail.index.twig', $data_arr);

        $response = $this->response($this->html);


        return $response;
    }

}
