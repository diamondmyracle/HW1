<?php
    class CommentController extends BaseController
    {
        public function postParentComment() 
        {
            $strErrorDesc = '' ;
            $requestMethod = $_SERVER["REQUEST_METHOD"] ;
            if (strtoupper($requestMethod) == 'POST') {
                try 
                {
                    $json = file_get_contents("php://input") ;
                    $data = json_decode($json, true) ;

                    $list_id = $data["list_id"] ;
                    $username = $data["username"] ;
                    $comment = $data["comment"] ;

                    $commentModel = new CommentModel();
                    $commentModel->insertParentComment($list_id, $username, $comment) ;
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
                        "message" => "Comment has been posted."
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

        public function postChildComment() 
        {
            $strErrorDesc = '' ;
            $requestMethod = $_SERVER["REQUEST_METHOD"] ;
            if (strtoupper($requestMethod) == 'POST') {
                try 
                {
                    $json = file_get_contents("php://input") ;
                    $data = json_decode($json, true) ;

                    $list_id = $data["list_id"] ;
                    $username = $data["username"] ;
                    $comment = $data["comment"] ;
                    $parent_id = $data["parent_id"] ;

                    $commentModel = new CommentModel();
                    $commentModel->insertChildComment($list_id, $username, $comment, $parent_id) ;
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
                        "message" => "Comment has been posted."
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
?>
