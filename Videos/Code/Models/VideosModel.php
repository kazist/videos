<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Videos\Videos\Code\Models;

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
class VideosModel extends BaseModel {

    public function saveVideo($form) {

        $factory = new KazistFactory();

        $form = $this->fetchVideoData($form);

        $id = $factory->saveRecordByEntity('#__videos_videos', $form);

        if ($id) {
            $msg = 'Video Saved Successfully.';
            $factory->enqueueMessage($msg, 'info');
            $redirect = $factory->generateUrl('admin.videos.videos.edit', array('id' => $id));
        } else {

            $msg = 'Saving Business Failed.';
            $factory->enqueueMessage($msg, 'info');
            $redirect = $factory->generateUrl('admin.videos.videos.add');
        }

        return $redirect;
    }

    public function fetchVideoData($form) {

        if ($form['url']) {

            $url_query = array();

            $url_data = parse_url($form['url']);
            $url_query_arr = explode('&', $url_data['query']);

            foreach ($url_query_arr as $query_part) {
                $params_part = explode('=', $query_part);
                $url_query[$params_part[0]] = $params_part[1];
            }

            $url_host_arr = explode('.', $url_data['host']);

            if (is_array($url_host_arr)) {
                $form['type'] = $url_host_arr[1];
            }

            if (isset($url_query['v'])) {
                $form['code'] = $url_query['v'];
            }

            $noembed_url = 'http://noembed.com/embed?url=' . $form['url'];
            $json_data = file_get_contents($noembed_url);
            $fetched_data = json_decode($json_data);

            $form['title'] = ( $form['title'] != '') ? $form['title'] : $fetched_data->title;
            $form['description'] = ( $form['description'] != '') ? $form['description'] : $fetched_data->title;
            $form['image'] = $this->fetchThumbnail($form['code'], $form['image'], $fetched_data->thumbnail_url);
        }

        return $form;
    }

    public function fetchThumbnail($code, $image, $image_url) {

        $mediamanager = new MediaManager();

        $upload_url = $mediamanager->getUploadDir('videos/videos');
        $image_url_arr = array_reverse(explode('/', $image_url));
        $image_name = $code . '_' . $image_url_arr[0];

        $ch = curl_init($image_url);

        $fp = fopen(JPATH_ROOT . '/' . $upload_url . $image_name, 'w+');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        return $mediamanager->updateMedia($image, 'videos/videos', 'image', $image_name, $upload_url . $image_name);
    }

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

    public function getVideos($category_id = '') {

        $json = new Json();
        $query_obj = new Query();
        $factory = new KazistFactory();
        $db = $factory->getDatabase();
        $user = $factory->getUser();

        $json_object = $json->getJsonLite('video', 'video', 'video');
        $table_alias = $json_object->table_alias;

        $query = $db->getQuery(true);
        $query = $query_obj->getSqlQuery($json_object, $query, $is_view);


        if ($category_id) {
            $query->where($table_alias . '.category_id=' . $category_id);
        }

        $query->order($table_alias . '.id DESC');

        $db->setQuery($query, 0, 10);
        $records = $db->loadObjectList();

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
