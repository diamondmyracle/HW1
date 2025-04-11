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
        if (!isset($data['username']) || !isset($data['listing_name']) || 
            !isset($data['listing_descript']) || !isset($data['price']) || 
            !isset($data['image'])) {
            throw new InvalidArgumentException("Missing required fields");
        }

        // Validate price is a positive number
        if (!is_numeric($data['price']) || $data['price'] <= 0) {
            throw new InvalidArgumentException("Price must be a positive number");
        }

        // Handle base64 image data
        $imageData = $data['image'];
        if (strpos($imageData, 'data:image') === 0) {
            // Extract the base64 data
            $imageData = explode(',', $imageData)[1];
        }

        return $this->insert(
            "INSERT INTO listings (username, listing_name, listing_descript, price, image) VALUES (?, ?, ?, ?, ?)",
            ["sssis", 
                $data["username"], 
                $data["listing_name"], 
                $data["listing_descript"], 
                $data["price"], 
                $imageData
            ]
        );
    }

    public function updateListing($data)
    {
        return $this->update(
            "UPDATE listings SET listing_name = ?, listing_descript = ?, price = ? WHERE id = ?",
            ["ssis", $data['listing_name'], $data['listing_descript'], $data['price'], $data['id']]
        );
    }

    public function deleteListing($data)
    {
        if (!isset($data['id'])) {
            throw new InvalidArgumentException("Listing ID is required");
        }

        return $this->delete(
            "DELETE FROM listings WHERE id = ?",
            ["s", $data["id"]]
        );
    }


    
}
?>

