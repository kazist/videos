<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Videos\Playlists\Code\Models;

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
class PlaylistsModel extends BaseModel {

    public function savePlaylistDetail($playlist_id, $form) {

        $new_videos = $form['videos']['new'];
        $existing_videos = $form['videos']['exist'];

        $this->saveVideos($playlist_id, $existing_videos, 'exist');
        $this->saveVideos($playlist_id, $new_videos, 'new');
    }

    public function saveVideos($playlist_id, $videos, $type) {

        $existing_arr = array();
        $factory = new KazistFactory();

        if (!empty($videos)) {

            foreach ($videos as $key => $video) {

                $data_obj = new \stdClass();
                $data_obj->video_id = $video;
                $data_obj->playlist_id = $playlist_id;

                $existing_arr[] = $factory->saveRecord('#__videos_playlists_videos', $data_obj, array('video_id=:video_id', 'playlist_id=:playlist_id'), $data_obj);
            }

            if ($type == 'exist') {
                $this->deleteRemovedVideos($playlist_id, $existing_arr);
            }
        }
    }

    public function deleteRemovedVideos($playlist_id, $existing_arr) {

        $existing_arr = array_unique($existing_arr);

        $query = new Query();
        $query->delete('#__videos_playlists_videos');
        $query->where('playlist_id=:playlist_id');
        $query->setParameter('playlist_id', $playlist_id);
        if (!empty($existing_arr)) {
            $query->andWhere('id NOT IN  (' . implode(',', $existing_arr) . ')');
        }

        $query->execute();
        //  print_r((string)$query); exit;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

    public function getVideoAutoCompleteSetting() {

        $autocomplete = array();

        $autocomplete["parameters"]["general"] = array();
        $autocomplete["parameters"]["save"] = array("index" => "");
        $autocomplete["parameters"]["media"] = array();
        $autocomplete["parameters"]["payment"] = array();

        $autocomplete["parameters"]["source"] = array(
            "data_source" => "db_table",
            "join_field" => "id",
            "display_field" => array('title'),
            "join_table_name" => "videos_videos",
        );

        return $autocomplete;
    }

    public function getPlaylistVideosList($playlist_id) {

        $tmp_array = array();
        $videos = $this->getPlaylistVideos($playlist_id);

        foreach ($videos AS $key => $video) {

            $query = $this->getQueryBuilder('videos_videos', 'vv');

            $query->where('vv.id=' . (int) $video->video_id);

            $records = $query->loadObject();

            $tmp_array[] = $records;
        }

        return $tmp_array;
    }

    public function getVideo($video_id) {

        $query = $this->getQueryBuilder('videos_videos', 'vv');

        $query->where('vv.id=' . (int) $video_id);

        $record = $query->loadObject();

        return $record;
    }

    public function getPlaylistVideos($playlist_id) {

        $query = $this->getQueryBuilder('videos_playlists_videos', 'vpv');

        $query->where('vpv.playlist_id=' . (int) $playlist_id);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getCategoryVideos($category_id, $offset = 0, $limit = 6) {

        $json = new Json();
        $factory = new KazistFactory();
        $db = $factory->getDatabase();
        $user = $factory->getUser();

        $json_object = $json->getJson('video', 'video', 'video');
        $table_alias = $json_object->table_alias;

        $query = $db->getQuery(true);
        $query = $this->getSqlQuery($json_object, $query, $is_view);

        if ($category_id) {
            $query->where($table_alias . '.category_id=' . $category_id);
        } else {
            $query->where('1=-1');
        }

        $query->order($table_alias . '.id DESC');

        $db->setQuery($query, $offset, $limit);
        $records = $db->loadObjectList();

        return $records;
    }

}
