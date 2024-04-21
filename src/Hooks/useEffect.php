<?php

namespace Bolero\Forms\Hooks;

function useEffect($callback, ...$params): void
{
    call_user_func($callback, ...$params);
}
