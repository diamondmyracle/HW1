<?php
class FavoritesController extends BaseController
{
    public function addFavorite()
    {
        $strErrorDesc = '';
        $strErrorHeader = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            try {
                $json = file_get_contents("php://input");
                $data = json_decode($json, true);

                $listingId = $data['listing_id'] ?? null;
                $username = $data['username'] ?? null;

                if (!$listingId || !$username) {
                    throw new Exception('Missing required parameters.');
                }

                $model = new FavoritesModel();
                $result = $model->addFavorite($username, $listingId);

                if ($result) {
                    $this->sendOutput(
                        json_encode([
                            "status" => "success",
                            "message" => "Favorite added"
                        ]),
                        ['Content-Type: application/json', 'HTTP/1.1 201 Created']
                    );
                    return;
                } else {
                    throw new Exception('Failed to add favorite.');
                }
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 400 Bad Request';
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Only POST method allowed.';
            $strErrorHeader = 'HTTP/1.1 405 Method Not Allowed';
        }

        $this->sendOutput(
            json_encode(["status" => "error", "message" => $strErrorDesc]),
            ['Content-Type: application/json', $strErrorHeader]
        );
    }

    public function deleteFavorite()
    {
        $strErrorDesc = '';
        $strErrorHeader = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'DELETE') {
            try {
                $json = file_get_contents("php://input");
                $data = json_decode($json, true);

                $listingId = $data['listing_id'] ?? null;
                $username = $data['username'] ?? null;

                if (!$listingId || !$username) {
                    throw new Exception('Missing required parameters.');
                }

                $model = new FavoritesModel();
                $result = $model->removeFavorite($username, $listingId);

                if ($result) {
                    $this->sendOutput(
                        json_encode([
                            "status" => "success",
                            "message" => "Favorite removed"
                        ]),
                        ['Content-Type: application/json', 'HTTP/1.1 200 OK']
                    );
                    return;
                } else {
                    throw new Exception('Failed to remove favorite.');
                }
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 400 Bad Request';
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Only DELETE method allowed.';
            $strErrorHeader = 'HTTP/1.1 405 Method Not Allowed';
        }

        $this->sendOutput(
            json_encode(["status" => "error", "message" => $strErrorDesc]),
            ['Content-Type: application/json', $strErrorHeader]
        );
    }

    public function getFavoriteCount()
    {
        $json = file_get_contents("php://input") ;
        $data = json_decode($json, true) ;

        $listingId = $data['listing_id'];

        if (!$listingId) {
            $this->sendOutput(
                json_encode(['status' => 'error', 'message' => 'Missing listing_id']),
                ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']
            );
            return;
        }

        $model = new FavoritesModel();
        $count = $model->getFavoriteCount($listingId);

        $this->sendOutput(
            json_encode(['status' => 'success', 'favorites' => $count[0]['favorites']]),
            ['Content-Type: application/json', 'HTTP/1.1 200 OK']
        );
    }

    public function isFavorited()
    {
        $json = file_get_contents("php://input") ;
        $data = json_decode($json, true) ;

        $listingId = $data['listing_id'] ?? null;
        $username = $data['username'] ?? null;

        if (!$listingId || !$username) {
            $this->sendOutput(
                json_encode(['favorited' => false]),
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
            return;
        }

        $model = new FavoritesModel();
        $result = $model->isFavorited($username, $listingId);

        $this->sendOutput(
            json_encode(['favorited' => !empty($result)]),
            ['Content-Type: application/json', 'HTTP/1.1 200 OK']
        );
    }

    public function getUserFavorites()
    {
        $json = file_get_contents("php://input") ;
        $data = json_decode($json, true) ;
        $username = $data['username'] ?? null;

        if (!$username) {
            $this->sendOutput(
                json_encode(['status' => 'error', 'message' => 'User not logged in']),
                ['Content-Type: application/json', 'HTTP/1.1 401 Unauthorized']
            );
            return;
        }

        $model = new FavoritesModel();
        $favorites = $model->getUserFavorites($username);

        $this->sendOutput(
            json_encode(['status' => 'success', 'favorites' => $favorites]),
            ['Content-Type: application/json', 'HTTP/1.1 200 OK']
        );
    }
}
?>
