<?php
    class ImageController extends BaseController
    {
        public function handleImageUpload() 
        {
            $strErrorDesc = '' ;
            $requestMethod = $_SERVER["REQUEST_METHOD"] ;
            if (strtoupper($requestMethod) == 'POST') {
                try {
                    $json = file_get_contents("php://input") ;
                    $data = json_decode($json, true) ;

                    $imageBase64 = $data["image"] ;
                    $decodedImage = base64_decode($imageBase64) ;

                    //$allowedExtensions = ['jpg', 'jpeg', 'png'] ;
                    //$fileExtension = strtolower(pathinfo($decodedImage['name'], PATHINFO_EXTENSION)) ;


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
                        ["encoded-data" => $imageBase64],
                        ["decoded-data" => $decodedImage]
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

            /*
            $imageFilename = "" ;

            //Did the image upload without an error?
            // if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            //     $error = "File upload failed with error code: " . $_FILES['image']['error'] ;
            //     $strErrorHeader = 'HTTP/1.1 500 Internal Server Error' ;
            //     $this->sendOutput(
            //         json_encode(['error' => $error]),
            //         ['Content-Type: application/json', $strErrorHeader]
            //     ) ;
            //     return ;
            // }

            $allowedExtensions = ['jpg', 'jpeg', 'png'] ;
            $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION)) ;

            //Did the image have a valid file type?
            if (!in_array($fileExtension, $allowedExtensions)) {
                $error = "Invalid file type. Only JPG, JPEG, and PNG files are allowed." ;
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error' ;
                $this->sendOutput(
                    json_encode(['error' => $error]),
                    ['Content-Type: application/json', $strErrorHeader]
                ) ;
                return ;
            }

            $imageFilename = $param_id . "_" . basename($_FILES['image']['name']) ;
            $targetDir = __DIR__ . "/uploads/" ;
            $targetFilePath = $targetDir . $imageFilename ;

            // Ensure uploads directory exists
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true) ;
            }

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $error = "Failed to upload image. Temp file: " . $_FILES['image']['tmp_name'] . 
                        " Target: " . $targetFilePath ;
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error' ;
                $this->sendOutput(
                    json_encode(['error' => $error]),
                    ['Content-Type: application/json', $strErrorHeader]
                ) ;
                return ;
            }

            //return $imageFilename ;
            */
        }
    }
?>
