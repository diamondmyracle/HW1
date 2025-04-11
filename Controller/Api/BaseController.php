<?php
    class BaseController
    {
        public function __call($name, $arguments)
        {
            $this->sendOutput('', array('404 Not Found')) ;
        }

        protected function getUriSegments()
        {
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ;
            $uri = explode('/', $uri);

            return $uri;
        }

        protected function getQueryStringParams()
        {
            $query = array();
            parse_str($_SERVER['QUERY_STRING'], $query);
            return $query;
        }

        protected function sendOutput($data, $httpHeaders=array())
        {
            header_remove('Set-Cookie') ;

            if (is_array($httpHeaders) && count($httpHeaders)) {
                foreach ($httpHeaders as $httpHeader) {
                    header($httpHeader) ;
                }
            }

            echo $data ;
            exit ;
        }

        protected function getUsername()
        {
            // First check for session authentication
            if (isset($_SESSION['username'])) {
                return $_SESSION['username'];
            }

            // Then check for Bearer token
            $headers = getallheaders();
            if (isset($headers['Authorization'])) {
                $token = str_replace('Bearer ', '', $headers['Authorization']);
                // For this example, we'll treat the token as the username
                // In a real application, you would validate the token properly
                return $token;
            }

            return null;
        }

        protected function authenticate()
        {
            $username = $this->getUsername();
            if (!$username) {
                $this->sendOutput(
                    json_encode(['error' => 'Authentication required']),
                    ['Content-Type: application/json', 'HTTP/1.1 401 Unauthorized']
                );
                exit;
            }
            return $username;
        }
    }
?>