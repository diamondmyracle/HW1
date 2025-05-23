<?php
    class ImageController extends BaseController
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

                    if (strpos($imageBase64, 'base64,') !== false) {
                        $imageBase64 = explode('base64,', $imageBase64)[1] ;
                    }
                    $decodedImage = base64_decode($imageBase64) ;

                    $allowedExtensions = ['jpg', 'jpeg', 'png'] ;
                    $fileExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION)) ;

                    if (!in_array($fileExtension, $allowedExtensions)) {
                        $error = "Invalid file type. Only JPG, JPEG, and PNG files are allowed." ;
                        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error' ;
                        $this->sendOutput(
                            json_encode(['error' => $error]),
                            ['Content-Type: application/json', $strErrorHeader]
                        ) ;
                        return ;
                    } else {
                        $imageFilename = uniqid("upload_") . "." . $fileExtension ;
                        $targetDir = PROJECT_ROOT_PATH . "/uploads/" ;
                        $targetFilePath = $targetDir . $imageFilename ;

                        // // Ensure uploads directory exists
                        // if (!is_dir($targetDir)) {
                        //     mkdir($targetDir, 0755, true) ;
                        // }

                        file_put_contents($targetFilePath, $decodedImage) ;
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
                        "message" => "Image has been uploaded.",
                        "filename" => $imageFilename
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
