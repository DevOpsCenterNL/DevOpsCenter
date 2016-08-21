<?php

namespace DevOpsCenter\Service;

/**
 * Class Slack
 * @package DevOpsCenter\Service
 */
class Slack
{
    /**
     * @var string
     */
    protected $team;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var
     */
    protected $info;

    /**
     * Slack constructor.
     * @param $team
     * @param $token
     */
    public function __construct($team, $token)
    {
        $this->team = $team;
        $this->token = $token;

//        $this->refresh();
    }

    /**
     *
     */
    public function refresh()
    {
        $url = 'https://' . $this->team . '.slack.com/api/rtm.start?token=' . $this->token;
        $info = file_get_contents($url);
        $this->info = json_decode($info, true);
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
        if (! isset($this->info['team']['icon']['image_'.$width])) {
            return null;
        }
        return $this->info['team']['icon']['image_'.$width];
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
//        $total = 0;
//        $active = 0;
//
//        foreach ($this->info['users'] as $user) {
//            $total++;
//            if ($user['presence'] == 'active') {
//                $active++;
//            }
//        }
        return array('total' => 2, 'active' => 2);
    }

    /**
     * @param $email
     * @return mixed
     */
    public function invite($email)
    {
        $url = 'https://' . $this->team . '.slack.com/api/users.admin.invite';

        $data = array ('token' => $this->token, 'email' => $email);
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