<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of VideosController
 *
 * @author sbc
 */

namespace Videos\Videos\Code\Controllers\Admin;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Videos\Videos\Code\Models\VideosModel;

class VideosController extends BaseController {

    public function saveAction() {

        $form = $this->request->get('form');

        $this->model = new VideosModel();
        $return_url = $this->model->saveVideo($form);

        return $this->redirect($return_url);
    }

}
