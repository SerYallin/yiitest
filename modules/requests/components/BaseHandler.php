<?php

namespace app\modules\requests\components;

/**
 * Base handler.
 */
abstract class BaseHandler
{
    /**
     * Errors storage.
     *
     * @var array $_errors
     */
    protected static array $errors = [];

    /**
     * Add new error.
     *
     * @param string $error
     */
    public static function addError(string $error): void
    {
        static::$errors[] = $error;
    }

    /**
     * Get errors.
     *
     * @return array
     *    Array of errors.
     */
    public static function getErrors(): array
    {
        return static::$errors;
    }

    /**
     * Check if handler has errors.
     *
     * @return bool
     */
    public static function hasError(): bool
    {
        return !empty(static::$errors);
    }
}
