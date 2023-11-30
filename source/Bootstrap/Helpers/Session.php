<?php

/*
|--------------------------------------------------------------------------
| Session Start
|--------------------------------------------------------------------------
*/

$session = session();

/**
 * @return \Source\Support\Session
 */
function session(): \Source\Support\Session
{
    return new \Source\Support\Session();
}
