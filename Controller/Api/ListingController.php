<?php
class ListingController extends BaseController
{
    /** 
     * "/listing/list" Endpoint - Get list of listings 
     */
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $listingModel = new ListingModel();
                $intLimit = 10;
                if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                    $intLimit = $arrQueryStringParams['limit'];
                }
                $arrListings = $listingModel->getListings($intLimit);
                $responseData = json_encode($arrListings);

            } catch (Error $e) {
                $strErrorDesc = $e->getMessage() . ' Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
        } else {
            $this->sendOutput(
                json_encode(['error' => $strErrorDesc]),
                ['Content-Type: application/json', $strErrorHeader]
            );
        }
    }

public function createAction()
{
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    if (strtoupper($requestMethod) === 'POST') {
        try {
            $listingModel = new ListingModel();

            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            $result = $listingModel->createListing($data);

            if ($result) {
                $this->sendOutput(json_encode([
                    'status' => 'success',
                    'message' => 'Listing created'
                ]), ['Content-Type: application/json']);
            } else {
                $this->sendOutput(json_encode([
                    'status' => 'error',
                    'message' => 'Failed to create listing.'
                ]), ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']);
            }
        } catch (Exception $e) {
            $this->sendOutput(json_encode([
                'status' => 'error',
                'message' => 'Exception: ' . $e->getMessage()
            ]), ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']);
        }
    } else {
        $this->sendOutput(json_encode([
            'status' => 'error',
            'message' => 'Method not allowed'
        ]), ['Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed']);
    }
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
