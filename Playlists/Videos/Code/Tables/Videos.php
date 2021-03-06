<?php

namespace Videos\Playlists\Videos\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Videos
 *
 * @ORM\Table(name="videos_playlists_videos", indexes={@ORM\Index(name="playlist_id_index", columns={"playlist_id"}), @ORM\Index(name="video_id_index", columns={"video_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Videos extends \Kazist\Table\BaseTable {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="playlist_id", type="integer", length=11, nullable=false)
     */
    protected $playlist_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="video_id", type="integer", length=11, nullable=false)
     */
    protected $video_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", length=11, nullable=false)
     */
    protected $created_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    protected $date_created;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", length=11, nullable=false)
     */
    protected $modified_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=false)
     */
    protected $date_modified;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set playlist_id
     *
     * @param integer $playlistId
     * @return Videos
     */
    public function setPlaylistId($playlistId) {
        $this->playlist_id = $playlistId;

        return $this;
    }

    /**
     * Get playlist_id
     *
     * @return integer 
     */
    public function getPlaylistId() {
        return $this->playlist_id;
    }

    /**
     * Set video_id
     *
     * @param integer $videoId
     * @return Videos
     */
    public function setVideoId($videoId) {
        $this->video_id = $videoId;

        return $this;
    }

    /**
     * Get video_id
     *
     * @return integer 
     */
    public function getVideoId() {
        return $this->video_id;
    }

    /**
     * Get created_by
     *
     * @return integer 
     */
    public function getCreatedBy() {
        return $this->created_by;
    }

    /**
     * Get date_created
     *
     * @return \DateTime 
     */
    public function getDateCreated() {
        return $this->date_created;
    }

    /**
     * Get modified_by
     *
     * @return integer 
     */
    public function getModifiedBy() {
        return $this->modified_by;
    }

    /**
     * Get date_modified
     *
     * @return \DateTime 
     */
    public function getDateModified() {
        return $this->date_modified;
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate() {
        // Add your code here
    }

}
