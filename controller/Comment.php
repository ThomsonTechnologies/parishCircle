<?php
class Comment {
        public static function createComment($commentBody, $postId, $userId) {

                if (strlen($commentBody) > 1000 || strlen($commentBody) < 1) {
                        die('Incorrect length!');
                }

                if (!DB::query('SELECT id FROM parishcircle.posts WHERE id=:postid', array(':postid'=>$postId))) {
                        echo 'Invalid post ID';
                } else {
                        DB::query('INSERT INTO parishcircle.comments VALUES (\'\', :comment, :userid, NOW(), :postid)', array(':comment'=>$commentBody, ':userid'=>$userId, ':postid'=>$postId));
                }

        }

        public static function displayComments($postId) {

                $comments = DB::query('SELECT * FROM parishcircle.comments WHERE post_id = :postid ORDER BY id DESC', array(':postid'=>$postId));
                // foreach($comments as $comment) {
                //         echo $comment['comment']." ~ ".$comment['username']."<hr />";
                // }

                return $comments;
        }
}
?>
