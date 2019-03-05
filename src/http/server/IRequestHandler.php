<?php

namespace Odin\http\server;

interface IRequestHandler
{
    public function handle(IRequest $request): IResponse;
}
