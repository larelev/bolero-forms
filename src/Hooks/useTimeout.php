<?php

namespace Bolero\Hooks;

function useTimeout($callback, $ms)
{
  useInterval($callback, $ms, 1);
}
