<?php
session_start();
    require_once 'inc/config.php';

    $reqMethod = $_SERVER["REQUEST_METHOD"] ;
    if ($reqMethod == "POST") //if the method is POST, then check for user/create endpoint
    {
        require __DIR__ . "/inc/bootstrap.php" ;
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ;
        $uri = explode('/', $uri) ;

        if (isset($uri[2])) {
            switch ($uri[2]) {
                case "image":
                    require PROJECT_ROOT_PATH . "/Controller/Api/ImageController.php" ;
                    $objFeedController = new ImageController() ;
                    $objFeedController->handleImageUpload() ;
                    exit() ;
                    break ;
                case "comment":
                    if (isset($uri[3])) {
                        switch ($uri[3]) {
                            case "post":
                                $json = file_get_contents("php://input") ;
                                $data = json_decode($json, true) ;
                                $parent_id = $data["parent_id"] ;

                                require PROJECT_ROOT_PATH . "/Controller/Api/CommentController.php" ;
                                $objFeedController = new CommentController() ;

                                if ($parent_id === "") {
                                    $objFeedController->postParentComment() ;
                                } else {
                                    $objFeedController->postChildComment() ;
                                }
                                exit() ;
                                break ;
                            case "delete":
                                require PROJECT_ROOT_PATH . "/Controller/Api/CommentController.php" ;
                                $objFeedController = new CommentController() ;
                                $objFeedController->deleteCommentById() ;
                                exit() ;
                                break ;
                            default:
                                header("HTTP/1.1 404 Not Found") ;
                                exit() ;
                        }
                    }
                    break ;
                default:
                    header("HTTP/1.1 404 Not Found") ;
                    exit() ;
            }
        } else {

        }
    }
?>

<!DOCTYPE html>
<HTML lang="en">
    <HEAD>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width">
        <meta name="description" content="Upload endpoint">
        <TITLE>Upload</TITLE>
    </HEAD>

    <BODY style="background-color:black ; color: green; background-image: url('photos/star_background.gif');">
        <style> 
            .ascii-art {
            font-family: monospace ;
            line-height: 1.1 ;
            white-space: pre-wrap ;
            }
        </style>

        <div class="ascii-art">
            <pre>
This is the upload endpoint cause I'm lazy and just felt like making a new file.
Here's some X-Files ASCII art cause I love The X-Files :)

        nHHHHHHnHHHn
       dHHHHHHHHHHHHHHb
      dHHHHHHHHHHHHHHHHb
     dHHHHHH~~  '~~9HHHHb               ....._________    _____.........__
     HHHHH~         ~9HHH             .'              :  :                :
     HHHH:           ~HHH             `.__..--.   :~--'   ~-.       __....'
     HHHP:_nnnn   .nn.HHP                     `-. `.         :   .-~
     `HH|:~~@ >) (- @>|P                        :. `.        :  :
      |^|:       :    |      %::$$HHHHHnn        `._:      .'  :
      :`|:       |    :    $$$$H$HH$HH$HHHHn        ..     :  :
       ~|:.   ((_))  .:  $:$$H:::H:HH:HHHHHHHb     :  `  _:  '
        ::`.   ~ ~   ': $:$:$H):(::: :  ~HH$HHn     -. ~~  .'
         ::   -~-~- . 'H$::$H((: : :  '   HH:H$       :   :
         `::   ~~~  '/ H$$$$H\: )`  `      H$H$      .'  :
         .|::..__..-~|HHDrSH:)_..._   .===.H$HH$    .' _ `.
       _/::::::      HHH$HHH: . _ .:  . _-.:HHH$   .' : `.`.
    ..::|:::::_    _HHHHHHHH: ` ~ '   ` ~-':H$$$H :  .'  : `.
 .:::::::|::../XxxX\.HHHHH^|:              :H::::H .-'    : `.
:::::::...|../\XXXX/\HHHHH\:::      ._)    :HH$$$H :       : `-.
:..........|/..\XX/..HHHHHHn::`.          ':HHHHHH ;       :    :
...............|XX|.HHHHHHHH$:     .=-=.   :HH$HH :        `.   :
...............XXXXHHH$HHH$HH::    `...'  .$HHH$H$'          :   :
...............XXXHHHH$HH:$HH$::.       ..H$HHHH$H          '.    :
..............|XXXHHHHHH$$HHH$H:::..   .':HHHHH$H$n--.   .---'     `.__.--.
..............|XXXXHHHHHHHHH$HH:::::~~~ :HHHHHHHHHHHn :  :                 :
..............|XHHHHHHHHHHHHHHHH:::    :HHHHHHHHHHHHHHn   -.___.-.___..DrS.'

------------------------------------------------
ASCII art from https://asciiart.website/index.php?art=television/x-files

        </div>

        <img src ="photos/construction.gif" 
            alt="under construction gif" 
            width = "200"
            height = "200">
    </BODY>
</HTML>