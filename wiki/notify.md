# Notify #

----------


## 发布 ##
往消息队列里添加100个元素

	foreach(range(1, 100) as $count) {
	
	    //第一个参数，'q1'为队列的routing_key，需要在prism端绑定相应的routing_key和访问prism的身份key
	    //第二个参数为数据，仅支持字符串
	    $r = $client->publish('q1', 'this is some message:' . $count);
	
	    echo "$count\n";
	
	    usleep(10*1000);
	
	}

## 获取 ##

往消息队列里获取(消费)元素并确认

	while(1) {
	
	    //访问队列，获取数据
	    $r = $client->consume();
	
	    //这里对获取到的数据进行处理
	    $r = json_decode($r);

	    echo "$r->body\n";
	
	    //处理完成后执行ack，表示处理成功，队列中的数据会删除
	    $client->ack($r->tag);
	
	}

[返回](index.md)