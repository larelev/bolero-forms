<?php

namespace Bolero\Forms\Hooks;

function useTimeout($callback, $ms)
{
    useInterval($callback, $ms, 1);
}
