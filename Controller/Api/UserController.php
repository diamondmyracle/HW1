<?php
class UserController extends BaseController
{
    /** 
* "/user/list" Endpoint - Get list of users 
*/
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();
                $intLimit = 10;
                if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                    $intLimit = $arrQueryStringParams['limit'];
                }
                $arrUsers = $userModel->getUsers($intLimit);
                $responseData = json_encode($arrUsers);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
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
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
        */
    }

    
    public function updateAction()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
    
        if (strtoupper($requestMethod) === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
    
            try {
                if (!isset($data['username'])) {
                    throw new Exception("Username not provided");
                }
    
                $userModel = new UserModel();
                $result = $userModel->getUserDescription($data['username']);
    
                if ($result) {
                    $this->sendOutput(
                        json_encode([$result]),
                        ['Content-Type: application/json']
                    );
                } else {
                    $this->sendOutput(
                        json_encode(["error" => "User not found"]),
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
        } elseif (strtoupper($requestMethod) === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
    
            try {
                $userModel = new UserModel();
                $result = $userModel->updateUser($data);
    
                if ($result) {
                    $this->sendOutput(
                        json_encode([
                            'success' => true,
                            'message' => 'User profile updated'
                        ]),
                        ['Content-Type: application/json']
                    );
                } else {
                    $this->sendOutput(
                        json_encode([
                            'success' => false,
                            'message' => 'No changes made or user not found.'
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
    


public function userExists()
    {
        $strErrorDesc = '' ;
        try {
            $userModel = new UserModel() ;

            $json = file_get_contents("php://input") ;
            $data = json_decode($json, true) ;

            $username = $data["username"] ;

            $result = $userModel->usernameInDatabase($username) ;
            $responseData = json_encode($result) ;
        } catch (Error $e) {
            $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
            $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                json_encode(
                    array_merge(
                        ["status" => "success"],
                        ["data" => json_decode($responseData)]
                    )),
                array('Content-Type: application/json', 'HTTP/1.1 201 OK')
            );
        } else {
            $this->sendOutput(
                json_encode([
                    "status" => "error",
                    ["error" => $strErrorDesc]
                ]),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function createUser()
    {
        $strErrorDesc = '' ;
        $requestMethod = $_SERVER["REQUEST_METHOD"] ;
        if (strtoupper($requestMethod) == 'POST') {
            try {
                $userModel = new UserModel() ;

                $json = file_get_contents("php://input") ;
                $data = json_decode($json, true) ;

                $username = $data["username"] ;
                $password = $data["psw"] ;

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT) ;
                $result = $userModel->insertUser($username, $hashedPassword) ;
                $responseData = json_encode($result) ;
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                json_encode([
                    "status" => "success",
                    "message" => "User has been created."
                ]),
                array('Content-Type: application/json', 'HTTP/1.1 201 OK')
            );
        } else {
            $this->sendOutput(
                json_encode([
                    "status" => "error",
                    ["error" => $strErrorDesc]
                ]),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
        
    }

    public function getUserData() 
    {
        $strErrorDesc = '' ;
        try {
            $userModel = new UserModel() ;

            $json = file_get_contents("php://input") ;
            $data = json_decode($json, true) ;

            $username = $data["username"] ;

            $result = $userModel->getUserInfo($username) ;
            $responseData = json_encode($result) ;
        } catch (Error $e) {
            $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
            $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                json_encode(
                    array_merge(
                        ["status" => "success"],
                        ["data" => json_decode($responseData)]
                    )),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode([
                    "status" => "error",
                    ["error" => $strErrorDesc]
                ]),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function loginUser()
    {
        $strErrorDesc = '' ;
        try {
            $userModel = new UserModel() ;

            $json = file_get_contents("php://input") ;
            $data = json_decode($json, true) ;

            $username = $data["username"] ;
            $password = $data["psw"] ;

            $message = "Invalid username or password." ;
            $status = "failure" ;

            $result = $userModel->getUserInfo($username) ;
            if (count($result) > 0) {
                $row = $result[0] ;
                $hashedPassword = $row["password"] ;

                if (password_verify($password, $hashedPassword)) {
                    $_SESSION["loggedin"] = true ;
                    $_SESSION["username"] = $username ;
                    $message = "Logged in." ;
                    $status = "success" ;
                }
            }
        } catch (Error $e) {
            $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
            $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                json_encode([
                    "status" => $status,
                    "data" => $message
                ]),
                array('Content-Type: application/json', 'HTTP/1.1 201 OK')
            );
        } else {
            $this->sendOutput(
                json_encode([
                    "status" => "error",
                    ["error" => $strErrorDesc]
                ]),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}
