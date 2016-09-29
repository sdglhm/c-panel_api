<?php


// Copyright (c) 2016 Lahiru Himesh

// Permission is hereby granted, free of charge, to any person obtaining a
// copy of this software and associated documentation files (the "Software"),
// to deal in the Software without restriction, including without limitation
// the rights to use, copy, modify, merge, publish, distribute, sublicense,
// and/or sell copies of the Software, and to permit persons to whom the
// Software is furnished to do so, subject to the following conditions:

// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.

// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
// FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
// DEALINGS IN THE SOFTWARE.


    /**
     * Better documentation added !
     */
namespace eezpal\cPanel_api;

/**
     * cPanel Class for handling.
     */
    class cPanel
    {
        private $host;
        private $username;
        private $hash;
        protected $headers = [];

        /**
         * [__construct Constructing cPanel api connection].
         *
         * @param array $options Passing user, host name and access hash for cPanel
         */
        public function __construct($options = [])
        {
            return $this->checkSettings($options)
                ->setHost($options['host'])
                ->setAuth($options['user'], $options['hash']);
        }

        /**
         * [__call Method invoking __call will build passed arguments].
         *
         * @param [type] $method [description]
         * @param [type] $arg    [description]
         *
         * @return [type] [description]
         */
        public function __call($method, $arg)
        {
            $this->buildArg($arg['0']);

            return $this->cpQuery($method);
        }

        /**
         * Check if settings are present with the passed variables.
         *
         * @param [] $options [description]
         *
         * @return [type] [description]
         */
        private function checkSettings($options)
        {
            if (empty($options['user'])) {
                throw new \Exception('Username is not set', 2301);
            }
            if (empty($options['hash'])) {
                throw new \Exception('Hash is not set', 2302);
            }
            if (empty($options['host'])) {
                throw new \Exception('CPanel Host is not set', 2303);
            }

            return $this;
        }

        public function setHost($host)
        {
            $this->host = $host;

            return $this;
        }

        public function setAuth($user, $hash)
        {
            $this->user = $user;
            $this->hash = $hash;

            return $this;
        }

        public function callHost()
        {
            return $this->host;
        }

        public function callUser()
        {
            return $this->user;
        }

        public function callHash()
        {
            return $this->hash;
        }

        public function setHeader($name, $value = '')
        {
            $this->headers[$name] = $value;

            return $this;
        }

        public function buildArg($arg)
        {
            $this->arg = http_build_query($arg);

            return $this;
        }

        private function makeHeader()
        {
            $headers = $this->headers;
            $user = $this->callUser();
            $hash = $this->callHash();

            return $headers['Authorization'] = 'WHM '.$user.':'.preg_replace("'(\r|\n)'", '', $hash);
        }

        protected function cpQuery($method)
        {
            $host = $this->callHost();
            $user = $this->callUser();
            $hash = $this->callHash();
            $arg = $this->arg;
            $query = $host.':2087/json-api/'.$method.'?api.version=1&'.$arg;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $header[0] = 'Authorization: WHM '.$user.':'.preg_replace("'(\r|\n)'", '', $hash);

            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_URL, $query);

            $result = curl_exec($curl);
            curl_close($curl);

            return $result;
        }
    }
