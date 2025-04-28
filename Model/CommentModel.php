<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
class CommentModel extends Database
{
    public function insertParentComment($list_id, $username, $comment, $reactions)
    {
        return $this->insert("INSERT INTO comments (listing_id, username, comment, reactions) VALUES (?, ?, ?, ?)", 
                            ["isss", $list_id, $username, $comment, $reactions]) ;
    }

    public function insertChildComment($list_id, $username, $comment, $parent_id, $reactions)
    {
        return $this->insert("INSERT INTO comments (listing_id, username, comment, parent_id, reactions) VALUES (?, ?, ?, ?, ?)", 
                            ["issis", $list_id, $username, $comment, $parent_id, $reactions]) ;
    }

    public function deleteComment($comment_id)
    {
        return $this->delete("DELETE FROM comments WHERE id = ?", ["i", $comment_id]) ;
    }

    public function getChildComments($comment_id)
    {
        return $this->select("SELECT * FROM comments WHERE parent_id = ?", ["i", $comment_id]) ;
    }

    public function getCommentsByListing($list_id)
    {
        return $this->select("SELECT * FROM comments WHERE listing_id = ?", ["i", $list_id]) ;
    }

    public function getCommentById($comment_id)
    {
        return $this->select("SELECT * FROM comments WHERE id = ?", ["i", $comment_id]) ;
    }

    public function updateReactionById($comment_id, $reactions)
    {
        return $this->update("UPDATE comments SET reactions = ? WHERE id = ?", ["si", $reactions, $comment_id]) ;
    }
}
?>
