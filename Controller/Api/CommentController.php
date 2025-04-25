<?php
    class CommentController extends BaseController
    {
        public function handleImageUpload() 
        {
            $strErrorDesc = '' ;
            $requestMethod = $_SERVER["REQUEST_METHOD"] ;
            if (strtoupper($requestMethod) == 'POST') {
                try 
                {
                    $json = file_get_contents("php://input") ;
                    $data = json_decode($json, true) ;

                    $imageName = $data["name"] ;
                    $imageBase64 = $data["image"] ;

                    }
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
                        "message" => "Image has been uploaded."
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
