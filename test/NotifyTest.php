<?php

require_once(__DIR__ . '/TestBase.php');

/*
    Notify消息队列测试

    用途：
    测试SDK的Notify(基于RabbitMQ)功能

    功能：
    publish发布内容到队列里
    consume获取队列内容/消费队列
    ack确认内容获取成功
*/
class CurlTest extends TestBase  {

    function setUp() {
        $this->client = new PrismClient($this->remote_url, $this->client_id, $this->secret);
    }

    public function testPublish() {

        $r = array();

        foreach(range(1, 100) as $count) {
            $r[] = $this->client->publish('q1', 'this is some message:' . $count);
            usleep(10*1000);
        }

        $this->assertEquals(100, count($r));

    }

    /**
     * @depends testPublish
     */
    public function testConsume() {

        $results = array();

        foreach(range(1, 100) as $count) {
            $r = $this->client->consume();
            $r = json_decode($r);
            $this->client->ack($r->tag);
            $results[] = $r;
        }

        $this->assertEquals(100, count($results));

    }

}
