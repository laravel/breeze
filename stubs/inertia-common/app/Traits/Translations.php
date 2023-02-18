<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

trait Translations
{
    /**
     * Retrieve all translation keys for the current locale from the PHP and JSON language files.
     */
    protected function translations(): array
    {
        $locale = App::getLocale();

        return Cache::rememberForever("translations_$locale", function () use ($locale) {
            $phpTranslations = [];
            $jsonTranslations = [];

            if (File::exists(base_path("lang/$locale"))) {
                $phpTranslations = collect(File::allFiles(base_path("lang/$locale")))
                    ->filter(function ($file) {
                        return $file->getExtension() === "php";
                    })->flatMap(function ($file) {
                        return Arr::dot([$file->getFilenameWithoutExtension() => File::getRequire($file->getRealPath())]);
                    })->toArray();
            }

            if (File::exists(base_path("lang/$locale.json"))) {
                $jsonTranslations = json_decode(File::get(base_path("lang/$locale.json")), true);
            }

            return array_merge($phpTranslations, $jsonTranslations);
        });
    }
}
