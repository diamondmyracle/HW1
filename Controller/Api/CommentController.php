<?php
    class CommentController extends BaseController
    {
        public function getListingComments()
        {
            $strErrorDesc = '' ;
            $requestMethod = $_SERVER["REQUEST_METHOD"] ;
            if (strtoupper($requestMethod) == 'POST') {
                try 
                {
                    $json = file_get_contents("php://input") ;
                    $data = json_decode($json, true) ;

                    $list_id = $data["list_id"] ;

                    $commentModel = new CommentModel();
                    $comments = $commentModel->getCommentsByListing($list_id) ;
                    $responseData = json_encode($comments) ;
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
                        ["data" => $responseData]
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
                    $reactions = json_encode(["like" => [], "love" => [], "laugh" => [], "mad" => []]) ;

                    $commentModel = new CommentModel();
                    $commentModel->insertParentComment($list_id, $username, $comment, $reactions) ;
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
                    $reactions = json_encode(["like" => [], "love" => [], "laugh" => [], "mad" => []]) ;

                    $commentModel = new CommentModel();
                    $commentModel->insertChildComment($list_id, $username, $comment, $parent_id, $reactions) ;
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

        public function deleteCommentById() 
        {
            $strErrorDesc = '' ;
            $requestMethod = $_SERVER["REQUEST_METHOD"] ;
            if (strtoupper($requestMethod) == 'POST') {
                try 
                {
                    $json = file_get_contents("php://input") ;
                    $data = json_decode($json, true) ;

                    $comment_id = $data["comment_id"] ;

                    $commentModel = new CommentModel();
                    $this->deleteCommentChain($comment_id, $commentModel) ;
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
                        "message" => "Comment has been deleted."
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

        private function deleteCommentChain($comment_id, $commentModel)
        {
                $children = $commentModel->getChildComments($comment_id) ;

                foreach ($children as $child) {
                    $child_id = $child["id"] ;
                    $this->deleteCommentChain($child_id, $commentModel) ;
                }

                $commentModel->deleteComment($comment_id) ;
        }

        public function saveCommentEdit() 
        {
            $strErrorDesc = '' ;
            $requestMethod = $_SERVER["REQUEST_METHOD"] ;
            if (strtoupper($requestMethod) == 'POST') {
                try 
                {
                    $json = file_get_contents("php://input") ;
                    $data = json_decode($json, true) ;

                    $comment_id = $data["comment_id"] ;
                    $comment = $data["comment"] ;

                    $commentModel = new CommentModel();
                    $commentModel->saveEditsToComment($comment_id, $comment) ;
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
                        "message" => "Comment has been edited."
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

        public function setReaction()
        {
            $strErrorDesc = '' ;
            $requestMethod = $_SERVER["REQUEST_METHOD"] ;
            if (strtoupper($requestMethod) == 'POST') {
                try 
                {
                    $json = file_get_contents("php://input") ;
                    $data = json_decode($json, true) ;

                    $comment_id = $data["comment_id"] ;
                    $reactType = $data["reactType"] ;
                    $username = $data["username"] ;

                    $reactions = $this->getReactionsFromComment($comment_id) ;
                    $reactions = json_decode($reactions, true) ;
                    $clearReactions = $this->clearReactionsOfUser($reactions, $username) ;

                    if ($reactType !== "none") {
                        $clearReactions[$reactType][] = $username ;
                    }

                    $commentModel = new CommentModel();
                    $commentModel->updateReactionById($comment_id, json_encode($clearReactions)) ;
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
                        "message" => "Reaction was set."
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

        private function getReactionsFromComment($comment_id)
        {
            $commentModel = new CommentModel();
            $comments = $commentModel->getCommentById($comment_id) ;
            return $comments[0]["reactions"] ;
        }

        private function clearReactionsOfUser($reactions, $username)
        {
            foreach ($reactions as $type => $users) {
                $index = array_search($username, $users) ;

                if ($index !== false) {
                    unset($reactions[$type][$index]) ;
                    $reactions[$type] = array_values($reactions[$type]) ;
                }
            }

            return $reactions ;
        }
    }
?>
