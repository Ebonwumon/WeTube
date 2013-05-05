<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ebon
 * Date: 04/05/13
 * Time: 19:31
 * To change this template use File | Settings | File Templates.
 */

class DB {
    private $pdo;

    //SUPER BAD FIX THIS BEFORE SELLING TO FACEBOOK
    public function __construct() {
        $user = "ebon";
        $pass = "wubase";
        $dsn = 'mysql:host=localhost;dbname=jukebox'; // PRODUCTION CHANGE
        $this->pdo = new PDO($dsn, $user, $pass);
    }




    //API FUNCS
    function getAllInVoteQueue($event_id) {
        $query = "SELECT users.name as username, queue.youtube_name as video_name, queue.vote
                 FROM users, queue
                 WHERE queue.in_event = :event_id
                 ORDER by queue.vote";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':event_id', $event_id);
        if (!$queryPrepared->execute()) {
            return array('error' => "could not get votes ". implode($queryPrepared->errorInfo()));
        }
        return $queryPrepared->fetchAll();
    }

    function getNowPlaying($event_id) {
        $query = "SELECT ID, youtube_name
                  FROM queue_history
                  WHERE in_event = :event_id
                  ORDER by DATE DESC";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':event_id', $event_id);
        if (!$queryPrepared->execute()) {
            return array('error' => 'could not get currently playing '. implode($queryPrepared->errorInfo()));
        }
        return $queryPrepared->fetch();
    }

    function createEvent($user_id, $name, $desc) {
        $query = "INSERT into events (name, description, creator) VALUES(:name, :desc, :user_id)";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':name', $name);
        $queryPrepared->bindValue(':desc', $desc);
        $queryPrepared->bindValue('user_id', $user_id);
        if (!$queryPrepared->execute()) {
            return array('error' => 'nothing to do here'. $queryPrepared->er);
        }
        $event_id = $this->pdo->lastInsertId();
        return array('event_id' => $event_id);
    }

    function vote($queue_id, $user_id, $vote_val) {
        $this->pdo->beginTransaction();
        $check = "SELECT user from user_votes
                  WHERE user = :user_id AND queue_item = :queue_id";
        $checkPrep = $this->pdo->prepare($check);
        if (!$checkPrep->execute(array(':user_id' => $user_id, ':queue_id' => $queue_id))) {
            return array('error' => 'could not check for prior votes '. implode($checkPrep->errorInfo()));
        }
        if ($checkPrep->rowCount() > 0) {
            return array ('error' => "can't vote twice");
        }
        $query = "UPDATE queue
                  SET vote = vote + :vote_val
                  WHERE ID = :queue_id";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':vote_val', $vote_val);
        $queryPrepared->bindValue(':queue_id', $queue_id);
        $queryPrepared->execute();
        $finally = "Insert into user_votes
                    VALUES (:user_id, :vote_val)";
        $finallyPrep = $this->pdo->prepare($finally);
        $finallyPrep->execute(array(':user_id' => $user_id, ':vote_val' => $vote_val));
        $this->pdo->commit();
        return array('message' => "success");
    }

    function addToQueue($event_id, $youtube_id, $youtube_name, $user_id) {
        $query = "INSERT into queue (user_id, youtube_id, youtube_name, in_event)
                  VALUES (:user_id, :youtube_id, :youtube_name, :event_id);";
        $queryPrepared = $this->pdo->prepare($query);
        if(!$queryPrepared->execute(array(':event_id' => $event_id, ':youtube_id' => $youtube_id, ':youtube_name' => $youtube_name, ':user_id' => $user_id))) {
            return array ('error' => 'Error adding ' . $queryPrepared->errorInfo());
        }
        return array ('message' => 'success');

    }

    function login($handle) {
        $query = "SELECT name from users where name = :handle";
        $queryPrepared = $this->pdo->prepare($query);
        $queryPrepared->bindValue(':handle', $handle);
        if (!$queryPrepared->execute()) {
            return $queryPrepared->errorInfo();
        }
        if ($queryPrepared->rowCount() == 0) {
            $insert = "INSERT INTO users (name) values (:handle)";
            $prepared = $this->pdo->prepare($insert);
            $prepared->bindValue(':handle', $handle);
            $prepared->execute();
            return array('username' => $handle);
        }
        return array ('username' => $handle);
    }

    function playNext($event_id) {
        //TODO make sure this is proper
        $this->pdo->beginTransaction();
        $query = "SELECT * from queue
                  WHERE in_event = :event_id AND vote = max(vote)";
    }



}

