<?php

class ApiTest extends TestCase {

    /**
     * Generic method for creating curl requests
     * @param  string $url    [description]
     * @param  string $method [description]
     * @return string         [description]
     */
    private function createRequest($url, $method) {
        $curl = curl_init();
        if ($method == 'PUT') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_PUT, 1);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $http_status;

    }

    /**
     * Test activity api requests
     * @return [type] [description]
     */
    public function testActivityApi()
    {
        $this->assertEquals(200, $this->createRequest('http://localhost/index.php/activity/11111', 'GET'));
        $this->assertEquals(400, $this->createRequest('http://localhost/index.php/activity/11%^&^', 'GET'));
        $this->assertEquals(404, $this->createRequest('http://localhost/index.php/activity/111', 'GET'));
    }

    /**
     * Test user api requests
     * @return [type] [description]
     */
    public function testUserApi()
    {
        $this->assertEquals(200, $this->createRequest('http://localhost/index.php/user/1', 'GET'));
        $this->assertEquals(400, $this->createRequest('http://localhost/index.php/user/1%^&', 'GET'));
        $this->assertEquals(404, $this->createRequest('http://localhost/index.php/user/dsadas', 'GET'));
    }
    /**
     * Test track api requests
     * @return [type] [description]
     */
    public function testTrackApi() {
        $this->assertEquals(200, $this->createRequest('http://localhost/index.php/track/sample/1/click', 'PUT'));
        $this->assertEquals(400, $this->createRequest('http://localhost/index.php/track/sample%^^&&/1/click', 'GET'));
        $this->assertEquals(404, $this->createRequest('http://localhost/index.php/track/ssadas/asdas/buy', 'GET'));
    }

}