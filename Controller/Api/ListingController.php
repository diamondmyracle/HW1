<?php
require_once PROJECT_ROOT_PATH . "/Model/ListingModel.php";

class ListingController extends BaseController
{
    private $listingModel;

    public function __construct()
    {
        $this->listingModel = new ListingModel();
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
                $intLimit = 10;
                if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                    $intLimit = $arrQueryStringParams['limit'];
                }
                $arrListings = $this->listingModel->getListings($intLimit);
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
                $username = $this->authenticate();
                
                $json = file_get_contents('php://input');
                $data = json_decode($json, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception('Invalid JSON data: ' . json_last_error_msg());
                }

                // Validate required fields
                if (!isset($data['listing_name']) || empty($data['listing_name'])) {
                    throw new Exception('Listing name is required');
                }
                if (!isset($data['listing_descript']) || empty($data['listing_descript'])) {
                    throw new Exception('Listing description is required');
                }
                if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
                    throw new Exception('Valid price is required');
                }
                if (!isset($data['image']) || empty($data['image'])) {
                    throw new Exception('Image is required');
                }

                // Set username from authentication
                $data['username'] = $username;

                // Handle base64 image data
                if (strpos($data['image'], 'data:image') === 0) {
                    // Extract the base64 data
                    $data['image'] = explode(',', $data['image'])[1];
                }

                $result = $this->listingModel->createListing($data);

                if ($result) {
                    $this->sendOutput(json_encode([
                        'message' => 'Listing created successfully'
                    ]), ['Content-Type: application/json', 'HTTP/1.1 200 OK']);
                } else {
                    throw new Exception('Failed to create listing in database');
                }
            } catch (Exception $e) {
                $this->sendOutput(json_encode([
                    'error' => $e->getMessage()
                ]), ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']);
            }
        } else {
            $this->sendOutput(json_encode([
                'error' => 'Method not allowed'
            ]), ['Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed']);
        }
    }

    public function getAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') {
            try {
                if (!isset($arrQueryStringParams['id'])) {
                    throw new Exception('Listing ID is required');
                }

                $listing = $this->listingModel->getListingById($arrQueryStringParams['id']);
                if (!$listing) {
                    throw new Exception('Listing not found');
                }

                $responseData = json_encode($listing);
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
        } else {
            $strErrorDesc = 'Method not allowed';
            $strErrorHeader = 'HTTP/1.1 405 Method Not Allowed';
        }

        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function updateAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'PUT') {
            try {
                $username = $this->authenticate();
                
                $rawData = file_get_contents('php://input');
                error_log("Raw update request data: " . $rawData);
                
                $data = json_decode($rawData, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception('Invalid JSON data: ' . json_last_error_msg());
                }

                error_log("Decoded update data: " . print_r($data, true));

                if (!isset($data['id'])) {
                    throw new Exception('Listing ID is required');
                }
                if (!isset($data['listing_name'])) {
                    throw new Exception('Listing name is required');
                }
                if (!isset($data['listing_descript'])) {
                    throw new Exception('Listing description is required');
                }
                if (!isset($data['price'])) {
                    throw new Exception('Price is required');
                }

                // Verify the user owns the listing
                $listing = $this->listingModel->getListingById($data['id']);
                if (!$listing) {
                    throw new Exception('Listing not found');
                }
                if ($listing['username'] !== $username) {
                    throw new Exception('Unauthorized to update this listing');
                }

                $result = $this->listingModel->updateListing(
                    $data['id'],
                    $data['listing_name'],
                    $data['listing_descript'],
                    $data['price']
                );

                if ($result) {
                    $this->sendOutput(
                        json_encode(['message' => 'Listing updated successfully']),
                        ['Content-Type: application/json', 'HTTP/1.1 200 OK']
                    );
                } else {
                    throw new Exception('Failed to update listing');
                }
            } catch (Exception $e) {
                $this->sendOutput(
                    json_encode(['error' => $e->getMessage()]),
                    ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']
                );
            }
        } else {
            $this->sendOutput(
                json_encode(['error' => 'Method not allowed']),
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
                $result = $this->listingModel->deleteListing($data);

                
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
}
?>
