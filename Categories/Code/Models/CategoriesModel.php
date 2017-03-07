<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Videos\Categories\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\Json\Json;
use Joomla\Uri\Uri;
use Kazist\Service\Media\MediaManager;
use Kazist\Service\Database\Query;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class CategoriesModel extends BaseModel {

    public function getRelatedVideo($video_id, $category_id) {

        $json = new Json();
        $factory = new KazistFactory();
        $db = $factory->getDatabase();
        $user = $factory->getUser();

        $json_object = $json->getJson('video', 'video', 'video');
        $table_alias = $json_object->table_alias;

        $query = $db->getQuery(true);
        $query = $this->getSqlQuery($json_object, $query, $is_view);

        if ($category_id) {
            $query->where($table_alias . '.category=' . $category_id);
        }

        if ($video_id) {
            $query->where($table_alias . '.id<>' . $video_id);
        }

        $query->order($table_alias . '.id DESC');

        $db->setQuery($query, 0, 6);
        $records = $db->loadObjectList();

        return $records;
    }

    public function getVideos($order = 'latest', $category_id = '', $limit = 6) {

        $query = $this->getQueryBuilder('videos_videos', 'vv');

        if ($category_id) {
            $query->where('vv.category_id=' . (int) $category_id);
        }

        if ($order == 'latest') {
            $query->orderBy('vv.id', 'DESC');
        }

        if ($order == 'popular') {
            $query->orderBy('vv.hits', 'DESC');
        }

        if ($order == 'featured') {
            $query->orderBy('vv.featured', 'DESC');
        }

        $query->setMaxResults($limit);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getCategories() {

        $query = $this->getQueryBuilder('videos_categories', 'vc');

        $query->orderBy('vc.title', 'ASC');

        $query->setMaxResults(4);

        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $key => $record) {
                $records[$key]->videos = $this->getCategoryVideos($record->id);
            }
        }


        return $records;
    }

    public function getCategoryVideos($category_id, $limit = 6) {

        $query = $this->getQueryBuilder('videos_videos', 'vv');

        $query->where('vv.category_id = :category_id');
        $query->setParameter('category_id', $category_id);
        $query->orderBy('vv.id', 'DESC');

        $query->setMaxResults($limit);

        $records = $query->loadObjectList();

        return $records;
    }

}
