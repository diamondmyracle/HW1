<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
class CommentModel extends Database
{
    public function insertParentComment($list_id, $username, $comment)
    {
        return $this->insert("INSERT INTO comments (listing_id, username, comment) VALUES (?, ?, ?)", 
                            ["iss", $list_id, $username, $comment]) ;
    }

    public function insertChildComment($list_id, $username, $comment, $parent_id)
    {
        return $this->insert("INSERT INTO comments (listing_id, username, comment, parent_id) VALUES (?, ?, ?, ?)", 
                            ["issi", $list_id, $username, $comment, $parent_id]) ;
    }

    public function deleteComment($comment_id)
    {
        if (!isset($comment_id)) {
            throw new InvalidArgumentException("Comment ID is required");
        }

        return $this->delete(
            "DELETE FROM comments WHERE id = ?",
            ["i", $comment_id]
        );
    }
}
?>
