<?php

declare(strict_types=1);

namespace App\Src\Provider;

use Lcobucci\JWT\Parser;
use Lcobucci\Clock\SystemClock;
use Dflydev\FigCookies\SetCookie;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Dflydev\FigCookies\Modifier\SameSite;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use League\Container\ServiceProvider\AbstractServiceProvider;

class SessionServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [SessionMiddleware::class];

    /**
     * @return void
     */
    public function register(): void
    {
        /**
         * ---------------------------------------------------------------------------------------
         * PSR-7 Storage-less HTTP Sessions
         * ---------------------------------------------------------------------------------------
         * Este Middleware habilita el uso de Sesiones sin utilizar super globales ni ficheros, es
         * una forma mas segura y permite solventar limitaciones de las sesiones convencionales,
         * los datos se almacenan en una cookie, no se debe almacenar información sensible.
         * ---------------------------------------------------------------------------------------
         * @see https://github.com/psr7-sessions/storageless
         */
        $this->getLeagueContainer()->add(SessionMiddleware::class, function () {
            return new SessionMiddleware(
                new Sha256(),
                env('APP_KEY'),
                env('APP_KEY'),
                SetCookie::create('psr7-session')
                    ->withSecure(false)
                    ->withHttpOnly(true)
                    ->withSameSite(SameSite::lax())
                    ->withPath('/'),
                new Parser(),
                1200,
                new SystemClock()
            );
        });
    }
}
