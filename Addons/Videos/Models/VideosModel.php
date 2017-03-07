<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Videos\Addons\Videos\Models;

use Kazist\KazistFactory;

class VideosModel {

    public function getInfo() {
        return 'Hello World!';
    }

    public function getVideos($limit) {

        $factory = new KazistFactory;

        $query = $factory->getQueryBuilder('#__videos_videos', 'vv');

        $query->andWhere('vv.published=1');
        $query->orderBy('vv.id ', 'DESC');

        $query->setFirstResult(0);
        $query->setMaxResults($limit);

        $records = $query->loadObjectList();


        return $records;
    }

}
