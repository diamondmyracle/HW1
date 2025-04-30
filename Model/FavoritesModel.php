<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
class FavoritesModel extends Database
{
    public function addFavorite($username, $listingId)
    {
        return $this->insert(
            "INSERT INTO favorites (username, listing_id, created_at) VALUES (?, ?, NOW())",
            ["si", $username, $listingId]
        );
    }
    public function removeFavorite($username, $listingId)
    {
        return $this->delete(
            "DELETE FROM favorites WHERE username = ? AND listing_id = ?",
            ["si", $username, $listingId]
        );
    }

    // Get the count of favorites for a specific listing
    public function getFavoriteCount($listingId)
    {
        return $this->select(
            "SELECT COUNT(*) AS favorites FROM favorites WHERE listing_id = ?",
            ["i", $listingId]
        );
    }

    // Check if a listing is favorited by a user
    public function isFavorited($username, $listingId)
    {
        return $this->select(
            "SELECT 1 FROM favorites WHERE username = ? AND listing_id = ? LIMIT 1",
            ["si", $username, $listingId]
        );
    }

    // Get all favorites for a specific user - will be useful on profile page
     public function getUserFavorites($username)
    {
        return $this->select(
            "SELECT l.id, l.listing_name, l.image, l.price 
             FROM favorites f
             JOIN listings l ON f.listing_id = l.id
             WHERE f.username = ?",
            ["s", $username]
        );
    }

}

?>
