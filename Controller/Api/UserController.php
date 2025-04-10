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

public function userExists()
    {
        $strErrorDesc = '' ;
        try {
            $userModel = new UserModel() ;

            $json = file_get_contents("php://input") ;
            $data = json_decode($json, true) ;

            $username = $data["username"] ;

            $result = $userModel->selectByUsername($username) ;
            $responseData = json_encode($result) ;
            return $responseData ;
        } catch (Error $e) {
            $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
            $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
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
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode([
                "status" => "error",
            ]), json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
        
    }
    
}
