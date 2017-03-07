<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of CategoriesController
 *
 * @author sbc
 */

namespace Videos\Categories\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Videos\Categories\Code\Models\CategoriesModel;

class CategoriesController extends BaseController {

    public function indexAction() {

        $this->model = new CategoriesModel;

        $categories = $this->model->getRecords($offset, $limit);

        foreach ($categories as $key => $category) {
            $categories[$key]->videos = $this->model->getCategoryVideos($category->id, 4);
        }

        $data_arr['show_messages'] = true;
        $data_arr['total'] = $this->model->getTotal();
        $data_arr['categories'] = $categories;

        $this->html = $this->render('Videos:Categories:Code:views:table.index.twig', $data_arr);

        $response = $this->response($this->html);


        return $response;
    }

    public function detailAction($id = '') {

        $this->model = new CategoriesModel;

        $item = $this->model->getRecord($id);
        $popular = $this->model->getVideos('popular', $id, 3);
        $latest = $this->model->getVideos('latest', $id, 3);
        $featured = $this->model->getVideos('featured', $id, 3);

        $data_arr['item'] = $item;
        $data_arr['popular'] = $popular;
        $data_arr['latest'] = $latest;
        $data_arr['featured'] = $featured;

        $this->html = $this->render('Videos:Categories:Code:views:detail.index.twig', $data_arr);

        $response = $this->response($this->html);


        return $response;
    }

}
