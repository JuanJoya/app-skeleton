<?php

namespace CustomMVC\Core;

interface Response
{
    /**
     * @param string $resource
     * @return string
     */
	public function render($resource);
}
