<?php

namespace App\Handlers;

use Illuminate\Http\Request;
use Swoole\Websocket\Frame;
use SwooleTW\Http\Server\Sandbox;
use SwooleTW\Http\Websocket\HandlerContract;
use SwooleTW\Http\Websocket\SocketIO\SocketIOParser;
use SwooleTW\Http\Websocket\Websocket;
use Throwable;

class WsHandler implements HandlerContract
{
    /**
     * @var SocketIOParser
     */
    private $payloadParser;

    public function __construct(SocketIOParser $payloadParser)
    {
        $this->payloadParser = $payloadParser;
    }

    /**
     * @inheritDoc
     */
    public function onOpen($fd, Request $request)
    {
        // TODO: Implement onOpen() method.
    }

    /**
     * @inheritDoc
     */
    public function onMessage(Frame $frame)
    {
        dump(123);
        $websocket = $this->app->make(Websocket::class);
        $sandbox = $this->app->make(Sandbox::class);

        try {
            // decode raw message via parser
            $payload = $this->payloadParser->decode($frame);

            $websocket->reset(true)->setSender(auth()->id());

            // enable sandbox
            $sandbox->enable();

            // dispatch message to registered event callback
            ['event' => $event, 'data' => $data] = $payload;

            dump($event);
            if ($websocket->eventExists('Ol_' . $event)) {
                $websocket->call($event, $data);
            }

        } catch (Throwable $e) {
            dump($e);
            $this->logServerError($e);
        } finally {
            // disable and recycle sandbox resource
            $sandbox->disable();
        }
    }

    /**
     * @inheritDoc
     */
    public function onClose($fd, $reactorId)
    {
        // TODO: Implement onClose() method.
    }
}
