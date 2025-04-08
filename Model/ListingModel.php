<?php
require_once PROJECT_ROOT_PATH . "Model/Database.php";

class ListingModel extends Database
{
    public function getListings($limit)
    {
        return $this->select(
            "SELECT id, username, listing_name, listing_descript, price, image FROM listings ORDER BY id DESC LIMIT ?",
            ["i", $limit]
        );
    }
    public function createListing($data)
{
    return $this->insert(
        "INSERT INTO listings (id, username, listing_name, listing_descript, price, image) VALUES (?, ?, ?, ?, ?, ?)",
        ["ssssis", 
            $data["id"], 
            $data["username"], 
            $data["listing_name"], 
            $data["listing_descript"], 
            $data["price"], 
            $data["image"]
        ]
    );
}

}
?>