<?php

namespace Bolero\Hooks;

function useEffect($callback, ...$params)
{
    call_user_func($callback, ...$params);
}
