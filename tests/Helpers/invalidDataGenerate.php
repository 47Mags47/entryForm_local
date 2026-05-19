<?php

use Faker\Factory as FakerFactory;

if (!function_exists('generateInvalidData')) {

    /**
     * Генерация невалидных значений по набору правил.
     */
    function generateInvalidData(string $field, array $rules): array
    {
        $faker = FakerFactory::create('ru_RU');
        $invalid = [];

        foreach ($rules as $rule) {
            if (!is_string($rule))
                continue;

            switch (true) {
                // ---- Обязательное поле ----
                case $rule === 'required':
                    $invalid[] = null;
                    $invalid[] = '';
                    break;

                // ---- Строка ----
                case $rule === 'string':
                    $invalid[] = 12345;
                    $invalid[] = true;
                    $invalid[] = ['массив вместо строки'];
                    break;

                // ---- Число ----
                case $rule === 'integer':
                    $invalid[] = 'текст';
                    $invalid[] = 12.34;
                    $invalid[] = ['не число'];
                    break;

                // ---- Массив ----
                case $rule === 'array':
                    $invalid[] = 'не массив';
                    $invalid[] = 123;
                    break;

                // ---- Минимальная длина ----
                case str_starts_with($rule, 'min:'):
                    $min = (int) explode(':', $rule)[1];
                    $invalid[] = str_repeat('a', max(0, $min - 1));
                    break;

                // ---- Максимальная длина ----
                case str_starts_with($rule, 'max:'):
                    $max = (int) explode(':', $rule)[1];
                    $invalid[] = str_repeat('a', $max + 1);
                    break;

                // ---- Проверка существования ----
                case str_starts_with($rule, 'exists'):
                    $invalid[] = 'null';
                    $invalid[] = 'строка вместо ID';
                    break;

                // ---- Регулярка ----
                case str_starts_with($rule, 'regex'):
                    $invalid[] = '123@#$abc';
                    $invalid[] = 'ENGtext';
                    break;

                // ---- URL ----
                case $rule === 'url':
                    $invalid[] = 'not_a_url';
                    $invalid[] = 'http:/bad.url';
                    break;
            }
        }

        return array_unique($invalid, SORT_REGULAR);
    }
}
