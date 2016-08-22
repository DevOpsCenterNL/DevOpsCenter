<?php

namespace DevOpsCenter\Service;

/**
 * Class Slack
 * @package DevOpsCenter\Service
 */
class Slack
{
    /**
     * Contains name of the slack team
     * @var string
     */
    protected $team;

    /**
     * Contains the token used for sending invites
     * @var string
     */
    protected $token;

    /**
     * Will contain the entire slack team's url
     * @var string
     */
    protected $infoUrl;

    /**
     * 5 minute cache setting
     */
    const FIVE_MINUTES = 300;

    /**
     * Will contain the API JSON response with information about the slack's team
     * @var string
     */
    protected $info;

    /**
     *
     * @var
     */
    protected $cache;

    /**
     * Slack constructor.
     * @param $team
     * @param $token
     * @param $cache
     */
    public function __construct($team, $token, $cache)
    {
        $this->team = $team;
        $this->token = $token;
        $this->cache = $cache;
        $this->infoUrl = 'https://' . $this->team . '.slack.com/api/rtm.start?token=' . $this->token;

        $this->refresh();
    }

    /**
     *
     */
    public function refresh()
    {
        if (!$this->cache->fetch(md5($this->infoUrl))) {
            $this->cache->store(md5($this->infoUrl), file_get_contents($this->infoUrl), self::FIVE_MINUTES);
        }

        $this->info = json_decode($this->cache->fetch(md5($this->infoUrl)), true);
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param int $width
     * @return null
     */
    public function getImageSrc($width = 132)
    {
        if (!isset($this->info['team']['icon']['image_' . $width])) {
            return null;
        }
        return $this->info['team']['icon']['image_' . $width];
    }

    /**
     * @return mixed
     */
    public function getTeamName()
    {
        return 'DevOpsCenter';
//        return $this->info['team']['name'];
    }

    /**
     * @return array
     */
    public function getUserCount()
    {
        $total = 0;
        $active = 0;

        foreach ($this->info['users'] as $user) {
            $total++;
            if ($user['presence'] == 'active') {
                $active++;
            }
        }
        return array('total' => $total, 'active' => $active);
    }

    /**
     * @param $email
     * @return mixed
     */
    public function invite($email)
    {
        $url = 'https://' . $this->team . '.slack.com/api/users.admin.invite';

        $data = array('token' => $this->token, 'email' => $email);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);

        $result = json_decode($result, true);
        return $result;
    }
}