<?php
class ListingController extends BaseController
{
    public function listingByID()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            try {
                $json = file_get_contents('php://input');
                $data = json_decode($json, true);

                $listingModel = new ListingModel();
                $listing = $listingModel->getListingByID($data);
                $responseData = json_encode($listing);

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
                $intLimit = 100;
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
                ]), ['Content-Type: application/json', 'HTTP/1.1 201 Created']);
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

    

public function updateAction()
{
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    if (strtoupper($requestMethod) === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $listingModel = new ListingModel();
            $result = $listingModel->updateListing($data);

            
            if ($result) {
                $this->sendOutput(
                    json_encode([
                        'success' => true,
                        'message' => 'Listing updated'
                    ]),
                    ['Content-Type: application/json']
                );
            } else {
                $this->sendOutput(
                    json_encode([
                        'success' => false,
                        'message' => 'No changes made or listing not found.'
                    ]),
                    ['Content-Type: application/json', 'HTTP/1.1 404 Not Found']
                );
            }
            

        } catch (Exception $e) {
            
            $this->sendOutput(
                json_encode([
                    'success' => false,
                    'message' => 'Exception: ' . $e->getMessage()
                ]),
                ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']
            );
            
        }
    } else {
        
        $this->sendOutput(
            json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ]),
            ['Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed']
        );
        
    }
}

public function deleteAction()
{
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    if (strtoupper($requestMethod) === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $listingModel = new ListingModel();
            $result = $listingModel->deleteListing($data);

            
            if ($result) {
                $this->sendOutput(
                    json_encode([
                        'success' => true,
                        'message' => 'Listing deleted'
                    ]),
                    ['Content-Type: application/json']
                );
            } else {
                $this->sendOutput(
                    json_encode([
                        'success' => false,
                        'message' => 'Listing not found'
                    ]),
                    ['Content-Type: application/json', 'HTTP/1.1 404 Not Found']
                );
            }
            

        } catch (Exception $e) {
            
            $this->sendOutput(
                json_encode([
                    'success' => false,
                    'message' => 'Exception: ' . $e->getMessage()
                ]),
                ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']
            );
            
        }
    } else {
        
        $this->sendOutput(
            json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ]),
            ['Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed']
        );
        
    }
}

public function transferOwnershipToBuyer()
{
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $result = '' ;

    if (strtoupper($requestMethod) === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $username = $data["username"] ;
            $list_id = $data["list_id"] ;

            $listingModel = new ListingModel();
            $result = $listingModel->updateListing($username, $list_id);
        } catch (Exception $e) {
            $this->sendOutput(
                json_encode([
                    'success' => false,
                    'message' => 'Exception: ' . $e->getMessage()
                ]),
                ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']
            );
        }
    } else {   
        $this->sendOutput(
            json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ]),
            ['Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed']
        );  
    }

    if ($result) {
        $this->sendOutput(
            json_encode([
                'success' => true,
                'message' => 'Listing updated'
            ]),
            ['Content-Type: application/json']
        );
    } else {
        $this->sendOutput(
            json_encode([
                'success' => false,
                'message' => 'Ownership could not be transfered.'
            ]),
            ['Content-Type: application/json', 'HTTP/1.1 404 Not Found']
        );
    }
}



}
?>
