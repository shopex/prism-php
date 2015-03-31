<?php
// Logging
class Logger {

    public function show ($request, $response) {

        echo @date("Y-m-d H:i:s", time()) . " request id: " . $request->getRequestID() . "\n";

    }
}
