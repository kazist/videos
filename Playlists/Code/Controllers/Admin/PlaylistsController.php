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

namespace Videos\Playlists\Code\Controllers\Admin;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Controller\BaseController;
use Videos\Playlists\Code\Models\PlaylistsModel;

class PlaylistsController extends BaseController {

    public function saveAction() {

        $factory = new KazistFactory();

        $form = $this->request->get('form');

        $this->model = new PlaylistsModel();
        $playlist_id = $this->model->save($form);


        if ($playlist_id) {

            $this->model->savePlaylistDetail($playlist_id, $form);

            $msg = $form['title'] . ' Video Save Successfully';
            $factory->enqueueMessage($msg, 'info');
            $redirect = $this->generateUrl('admin.videos.playlists.edit', array('id' => $playlist_id));
        } else {
            $msg = 'Video Not added due to some errors;';
            $factory->enqueueMessage($msg, 'error');
            $redirect = $this->generateUrl('admin.videos.playlists');
        }

        return $this->redirect($redirect);
    }

}
