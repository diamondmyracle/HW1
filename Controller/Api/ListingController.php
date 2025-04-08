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
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $listingModel = new ListingModel();
            $result = $listingModel->createListing($data);

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
