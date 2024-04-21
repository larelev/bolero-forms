<?php

namespace Bolero\Forms\Hooks;

function useTimeout($callback, $ms): void
{
    useInterval($callback, $ms, 1);
}
