<?php

class Post {

        public static function createPost($postbody, $loggedInUserId, $profileUserId) {

                $post_body = nl2br(htmlentities($postbody, ENT_QUOTES, 'UTF-8'));
                $loggedInUserId = Login::isLoggedIn();

                if (strlen($post_body) > 1000 || strlen($post_body) < 1) {
                    die('Incorrect length!');
                }
                if ($loggedInUserId == $profileUserId){
                  DB::query('INSERT INTO parishcircle.posts VALUES (\'\', :postbody, NOW(), :userid, 0)', array(':postbody'=>$post_body, ':userid'=>$profileUserId));
                } else {
                  die('Incorrect user!');
                }

        }

        public static function likePost($postId, $likerId) {
                if (!DB::query('SELECT user_id FROM parishcircle.post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId))){
                      DB::query('UPDATE parishcircle.posts SET likes=likes+1 WHERE id=:postid', array(':postid'=>$postId));
                      DB::query('INSERT INTO parishcircle.post_likes VALUES (\'\', :postid, :userid)', array(':postid'=>$postId, ':userid'=>$likerId));
                }else {
                      DB::query('UPDATE parishcircle.posts SET likes=likes-1 WHERE id=:postid', array(':postid'=>$postId));
                      DB::query('DELETE FROM parishcircle.post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId));
                }
        }

        public static function getAllPosts($parish_id) {
                $posts = DB::query("SELECT * FROM parishcircle.posts WHERE user_id IN
                     (SELECT u.id FROM parishcircle.users u INNER JOIN parishcircle.address a
                         ON u.id = a.userid WHERE (parish_id = :parishid AND category = 'parishioner')) ORDER BY id DESC", array(':parishid'=>$parish_id));

                return $posts;
        }

        public static function getParishPosts($parishhead_id) {
                $parish_posts = DB::query('SELECT * FROM parishcircle.posts WHERE user_id = :parisheadid ORDER BY id DESC', array(':parisheadid'=>$parishhead_id));

                return $parish_posts;
        }

        public static function getDiocesePosts($userid, $username, $loggedInUserId) {

                return $posts;
        }

}
?>
