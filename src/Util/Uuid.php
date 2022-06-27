<?php

namespace Olssonm\Swish\Util;

use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    public const DEFAULT = 'default';

    public const SWISH = 'swish';

    public static function make(string $format = self::SWISH): string
    {
        $uuid = RamseyUuid::uuid4();

        return self::format($uuid, $format);
    }

    public static function validate(string $uuid): bool
    {
        $uuid = self::format($uuid, self::DEFAULT);
        return RamseyUuid::isValid($uuid);
    }

    public static function format(string $uuid, string $format = self::DEFAULT): string
    {
        $parts = explode('-', $uuid);
        if (count($parts) == 1) {
            $default = function ($uuid) {
                $uuid = implode('-', sscanf($uuid, '%8s%4s%4s%4s%12s'));
                return $uuid;
            };
            $uuid = mb_strtolower($default($uuid), 'UTF-8');
        }

        switch ($format) {
            case self::SWISH:
                return mb_strtoupper(RamseyUuid::fromString($uuid)->getHex()->toString(), 'UTF-8');
            default:
                return $uuid;
        }
    }
}
