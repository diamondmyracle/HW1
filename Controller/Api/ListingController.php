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

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception('Invalid JSON data');
                }

                // Get username from session or request headers
                $username = null;
                if (isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];
                } else {
                    // Check for API token in headers
                    $headers = getallheaders();
                    if (isset($headers['Authorization'])) {
                        $token = str_replace('Bearer ', '', $headers['Authorization']);
                        // Here you would validate the token and get the username
                        // For now, we'll just use the token as the username for testing
                        $username = $token;
                    }
                }

                if (!$username) {
                    throw new Exception('Authentication required');
                }

                $data['username'] = $username;

                $result = $listingModel->createListing($data);

                if ($result) {
                    $this->sendOutput(json_encode([
                        'status' => 'success',
                        'message' => 'Listing created successfully'
                    ]), ['Content-Type: application/json']);
                } else {
                    throw new Exception('Failed to create listing in database');
                }
            } catch (InvalidArgumentException $e) {
                $this->sendOutput(json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]), ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']);
            } catch (Exception $e) {
                $this->sendOutput(json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
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
}
?>
