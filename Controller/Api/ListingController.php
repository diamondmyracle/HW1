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

        /* // send output
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
        */
    }

    public function createAction()
{
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    if (strtoupper($requestMethod) === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $listingModel = new ListingModel();
            $result = $listingModel->createListing($data);

            /*
            if ($result) {
                $this->sendOutput(
                    json_encode([
                        'success' => true,
                        'message' => 'Listing created',
                        'data' => $data // optional: echo back the listing
                    ]),
                    ['Content-Type: application/json']
                );
            } else {
                $this->sendOutput(
                    json_encode([
                        'success' => false,
                        'message' => 'Failed to create listing.'
                    ]),
                    ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']
                );
            }
                */
        } catch (Exception $e) {
            /*
            $this->sendOutput(
                json_encode([
                    'success' => false,
                    'message' => 'Exception: ' . $e->getMessage()
                ]),
                ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']
            );
            */
        }
    } else {
        /*
        $this->sendOutput(
            json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ]),
            ['Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed']
        );
        */
    }
}

    public function updateAction()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
    
        if (strtoupper($requestMethod) == 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            
            try {
                if (!isset($data['id'])) {
                    throw new Exception("Listing ID is required for update");
                }
    
                $listingModel = new ListingModel();
                $affectedRows = $listingModel->updateListing($data);
                /*
                if ($affectedRows > 0) {
                    $this->sendOutput(
                        json_encode(['success' => true, 'message' => 'Listing updated']),
                        ['Content-Type: application/json', 'HTTP/1.1 200 OK']
                    );
                } else {
                    $this->sendOutput(
                        json_encode(['success' => false, 'message' => 'No listing was updated']),
                        ['Content-Type: application/json', 'HTTP/1.1 404 Not Found']
                    );
                }
                */
            } catch (Exception $e) {
                /*
                $this->sendOutput(
                    json_encode(['success' => false, 'message' => $e->getMessage()]),
                    ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']
                );
                */
            }
        } else {
            /*
            $this->sendOutput(
                json_encode(['success' => false, 'message' => 'Method not allowed']),
                ['Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed']
            );
            */
        }
    }
    
    public function deleteAction()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
    
        if (strtoupper($requestMethod) == 'DELETE') {
            $data = json_decode(file_get_contents('php://input'), true);
            try {
                if (!isset($data['id'])) {
                    throw new Exception("Listing ID is required");
                }
    
                $listingModel = new ListingModel();
                $affectedRows = $listingModel->deleteListing($data);
                /*
                if ($affectedRows > 0) {
                    $this->sendOutput(
                        json_encode(['success' => true, 'message' => 'Listing deleted']),
                        ['Content-Type: application/json', 'HTTP/1.1 200 OK']
                    );
                } else {
                    $this->sendOutput(
                        json_encode(['success' => false, 'message' => 'Listing not found']),
                        ['Content-Type: application/json', 'HTTP/1.1 404 Not Found']
                    );
                }
                */
            } catch (Exception $e) {
                /*
                $this->sendOutput(
                    json_encode(['success' => false, 'message' => $e->getMessage()]),
                    ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']
                );
                */
            }
        } else {
            /*
            $this->sendOutput(
                json_encode(['success' => false, 'message' => 'Method not allowed']),
                ['Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed']
            );
            */
        }
    }

    
}
?>
